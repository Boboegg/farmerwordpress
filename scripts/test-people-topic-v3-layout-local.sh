#!/usr/bin/env bash
set -uo pipefail

PHP="${PHP:-$HOME/Library/Application Support/Local/lightning-services/php-8.3.30+1/bin/darwin-arm64/bin/php}"
BASE_URL="${BASE_URL:-https://fwdignity-local.local}"
THEME_DIR="${THEME_DIR:-$HOME/Local Sites/fwdignity-local/app/public/wp-content/themes/fwdignity}"

checks=0
failures=0

pass() {
    checks=$(( checks + 1 ))
    printf 'PASS  %s\n' "$1"
}

fail() {
    checks=$(( checks + 1 ))
    failures=$(( failures + 1 ))
    printf 'FAIL  %s\n' "$1" >&2
}

assert_equal() {
    local description="$1"
    local expected="$2"
    local actual="$3"

    if [ "$actual" = "$expected" ]; then
        pass "$description"
    else
        fail "$description (expected: $expected; actual: ${actual:-<empty>})"
    fi
}

if [ ! -x "$PHP" ]; then
    printf 'ENV   LocalWP PHP is unavailable: %s\n' "$PHP" >&2
    printf 'RESULT ENVIRONMENT ERROR (people/topic assertions were not evaluated)\n' >&2
    exit 2
fi

css="$THEME_DIR/assets/css/page-people-who-farm.css"
js="$THEME_DIR/assets/js/page-people-who-farm.js"
for required_file in "$css" "$js"; do
    if [ ! -f "$required_file" ]; then
        printf 'ENV   required theme asset is unavailable: %s\n' "$required_file" >&2
        printf 'RESULT ENVIRONMENT ERROR (people/topic assertions were not evaluated)\n' >&2
        exit 2
    fi
done

fetch() {
    local route="$1"
    curl --silent --show-error --fail --insecure --location \
        --connect-timeout 5 --max-time 20 "$BASE_URL/$route/"
}

hub_html="$(fetch 'people-who-farm')"
if [ -z "$hub_html" ]; then
    printf 'ENV   People Who Farm hub returned no HTML.\n' >&2
    printf 'RESULT ENVIRONMENT ERROR (people/topic assertions were not evaluated)\n' >&2
    exit 2
fi

hub_snapshot="$(printf '%s' "$hub_html" | "$PHP" -r '
    $html = stream_get_contents( STDIN );
    libxml_use_internal_errors( true );
    $dom = new DOMDocument();
    if ( ! $dom->loadHTML( $html ) ) {
        exit( 1 );
    }
    $xpath = new DOMXPath( $dom );
    $root = $xpath->query( "//main[contains(concat(\" \", normalize-space(@class), \" \"), \" pf-hub \" )]" )->item( 0 );
    $entries = $root ? $xpath->query( ".//*[contains(concat(\" \", normalize-space(@class), \" \"), \" pf-question-entry \" )]", $root ) : null;
    $labels = array();
    $topics = array();
    $hidden = 0;
    if ( $entries ) {
        foreach ( $entries as $entry ) {
            $heading = $xpath->query( ".//h3", $entry )->item( 0 );
            $labels[] = preg_replace( "/\\s+/u", " ", trim( $heading ? $heading->textContent : "" ) );
            $topics[] = $entry->getAttribute( "data-topic" );
            if ( $entry->hasAttribute( "hidden" ) || "true" === $entry->getAttribute( "aria-hidden" ) ) {
                $hidden++;
            }
        }
    }
    $images = $root ? $xpath->query( ".//img", $root ) : null;
    $sources = array();
    if ( $images ) {
        foreach ( $images as $image ) {
            $source = parse_url( $image->getAttribute( "src" ), PHP_URL_PATH );
            if ( is_string( $source ) && "" !== $source ) {
                $sources[] = $source;
            }
        }
    }
    $cards = $root ? $xpath->query( ".//article[contains(concat(\" \", normalize-space(@class), \" \"), \" pf-content-card \" )]", $root ) : null;
    $empty_cards = 0;
    if ( $cards ) {
        foreach ( $cards as $card ) {
            if ( 0 === $xpath->query( ".//a[@href]", $card )->length || 0 === $xpath->query( ".//h3[normalize-space(.)]", $card )->length ) {
                $empty_cards++;
            }
        }
    }
    echo "VERSION\t", $root ? $root->getAttribute( "data-layout-version" ) : "", PHP_EOL;
    echo "ENTRY_COUNT\t", $entries ? $entries->length : 0, PHP_EOL;
    echo "ENTRY_HIDDEN\t", $hidden, PHP_EOL;
    echo "TOPICS\t", implode( "|", $topics ), PHP_EOL;
    echo "LABELS\t", implode( "|", $labels ), PHP_EOL;
    echo "UNIQUE_TOPICS\t", count( array_unique( $topics ) ), PHP_EOL;
    echo "IMAGE_DUPLICATES\t", count( $sources ) - count( array_unique( $sources ) ), PHP_EOL;
    echo "EMPTY_CARDS\t", $empty_cards, PHP_EOL;
')"

hub_value() {
    local key="$1"
    awk -F '\t' -v key="$key" '$1 == key { sub(/^[^\t]*\t/, ""); print }' <<<"$hub_snapshot"
}

assert_equal 'hub renders the v3 layout' 'v3' "$(hub_value VERSION)"
assert_equal 'hub shows all five questions at once' '5' "$(hub_value ENTRY_COUNT)"
assert_equal 'hub keeps every question visible without interaction' '0' "$(hub_value ENTRY_HIDDEN)"
assert_equal 'hub uses the five controlled topic slugs once each' \
    'body-workload|changing-work|rural-support|risk-responsibility|knowledge-to-field' \
    "$(hub_value TOPICS)"
assert_equal 'hub shows the five approved question labels in order' \
    '身體如何承受農務？|農業工作正在怎麼改變？|誰在撐起農村？|風險由誰承擔？|知識如何走進田間？' \
    "$(hub_value LABELS)"
assert_equal 'hub has five distinct topic entries' '5' "$(hub_value UNIQUE_TOPICS)"
assert_equal 'hub does not repeat an image in its main content' '0' "$(hub_value IMAGE_DUPLICATES)"
assert_equal 'hub renders no empty article cards' '0' "$(hub_value EMPTY_CARDS)"

routes=(
    'body-and-farm-work|body-workload|workload'
    'changing-farm-work|changing-work|change-sequence'
    'rural-support|rural-support|support-network'
    'risk-and-responsibility|risk-responsibility|responsibility-ledger'
    'knowledge-in-the-field|knowledge-to-field|knowledge-path'
)
profiles=()

for route_spec in "${routes[@]}"; do
    IFS='|' read -r route topic_slug expected_profile <<<"$route_spec"
    topic_html="$(fetch "people-who-farm/$route")"
    if [ -z "$topic_html" ]; then
        fail "topic page returns rendered HTML: $route"
        continue
    fi

    snapshot="$(printf '%s' "$topic_html" | "$PHP" -r '
        $html = stream_get_contents( STDIN );
        libxml_use_internal_errors( true );
        $dom = new DOMDocument();
        if ( ! $dom->loadHTML( $html ) ) {
            exit( 1 );
        }
        $xpath = new DOMXPath( $dom );
        $root = $xpath->query( "//main[contains(concat(\" \", normalize-space(@class), \" \"), \" pf-topic-page \" )]" )->item( 0 );
        $cards = $root ? $xpath->query( ".//article[contains(concat(\" \", normalize-space(@class), \" \"), \" pf-content-card \" )]", $root ) : null;
        $post_ids = array();
        $empty_cards = 0;
        if ( $cards ) {
            foreach ( $cards as $card ) {
                $post_ids[] = $card->getAttribute( "data-post-id" );
                if ( 0 === $xpath->query( ".//a[@href]", $card )->length || 0 === $xpath->query( ".//h3[normalize-space(.)]", $card )->length ) {
                    $empty_cards++;
                }
            }
        }
        $images = $root ? $xpath->query( ".//img", $root ) : null;
        $sources = array();
        if ( $images ) {
            foreach ( $images as $image ) {
                $source = parse_url( $image->getAttribute( "src" ), PHP_URL_PATH );
                if ( is_string( $source ) && "" !== $source ) {
                    $sources[] = $source;
                }
            }
        }
        $empty_state_count = $root ? $xpath->query( ".//*[contains(concat(\" \", normalize-space(@class), \" \"), \" pf-empty-state \" )]", $root )->length : 0;
        $navigation_count = $root ? $xpath->query( ".//nav[contains(concat(\" \", normalize-space(@class), \" \"), \" pf-topic-navigation \" )]/a", $root )->length : 0;
        $current_count = $root ? $xpath->query( ".//nav[contains(concat(\" \", normalize-space(@class), \" \"), \" pf-topic-navigation \" )]/a[@aria-current=\"page\"]", $root )->length : 0;
        echo "TOPIC\t", $root ? $root->getAttribute( "data-topic" ) : "", PHP_EOL;
        echo "PROFILE\t", $root ? $root->getAttribute( "data-layout-profile" ) : "", PHP_EOL;
        echo "NAV_COUNT\t", $navigation_count, PHP_EOL;
        echo "CURRENT_COUNT\t", $current_count, PHP_EOL;
        echo "CARD_COUNT\t", $cards ? $cards->length : 0, PHP_EOL;
        echo "UNIQUE_POSTS\t", count( array_unique( $post_ids ) ), PHP_EOL;
        echo "EMPTY_CARDS\t", $empty_cards, PHP_EOL;
        echo "EMPTY_STATE\t", $empty_state_count, PHP_EOL;
        echo "IMAGE_DUPLICATES\t", count( $sources ) - count( array_unique( $sources ) ), PHP_EOL;
    ')"

    value() {
        local key="$1"
        awk -F '\t' -v key="$key" '$1 == key { sub(/^[^\t]*\t/, ""); print }' <<<"$snapshot"
    }

    profile="$(value PROFILE)"
    profiles+=( "$profile" )
    assert_equal "$route uses its controlled topic slug" "$topic_slug" "$(value TOPIC)"
    assert_equal "$route uses its distinct v3 layout profile" "$expected_profile" "$profile"
    assert_equal "$route removes the retired five-option topic strip" '0' "$(value NAV_COUNT)"
    assert_equal "$route has no retired current-topic marker" '0' "$(value CURRENT_COUNT)"
    assert_equal "$route does not duplicate canonical articles" "$(value CARD_COUNT)" "$(value UNIQUE_POSTS)"
    assert_equal "$route renders no empty article cards" '0' "$(value EMPTY_CARDS)"
    assert_equal "$route does not repeat an image in main content" '0' "$(value IMAGE_DUPLICATES)"

    card_count="$(value CARD_COUNT)"
    empty_state="$(value EMPTY_STATE)"
    if [ "$card_count" = '0' ]; then
        assert_equal "$route uses one honest zero-content state" '1' "$empty_state"
    else
        assert_equal "$route hides the zero-content state when articles exist" '0' "$empty_state"
    fi
done

profile_manifest="$(IFS='|'; printf '%s' "${profiles[*]}")"
assert_equal 'the five topic Pages use five different layout profiles' \
    'workload|change-sequence|support-network|responsibility-ledger|knowledge-path' \
    "$profile_manifest"

if rg -n -- '--pf-|#[0-9a-fA-F]{3,8}\b|rgba?\(|hsla?\(|oklch\(' "$css" >/dev/null; then
    fail 'People Who Farm CSS uses only canonical --fw aliases without private colors'
else
    pass 'People Who Farm CSS uses only canonical --fw aliases without private colors'
fi

if "$PHP" -r '$css=file_get_contents($argv[1]); exit(substr_count($css,"{")===substr_count($css,"}")?0:1);' "$css"; then
    pass 'People Who Farm CSS braces are balanced'
else
    fail 'People Who Farm CSS braces are balanced'
fi

if node --check "$js" >/dev/null; then
    pass 'People Who Farm JavaScript syntax is valid'
else
    fail 'People Who Farm JavaScript syntax is valid'
fi

if rg -n 'preventDefault|role[^\n]*tab|tabpanel|aria-selected|\.hidden\s*=' "$js" >/dev/null; then
    fail 'topic map JavaScript never turns first click into a preview-only action'
else
    pass 'topic map JavaScript never turns first click into a preview-only action'
fi

if rg -n 'data-topic-entry' "$js" >/dev/null; then
    pass 'topic map JavaScript enhances the visible topic entries'
else
    fail 'topic map JavaScript enhances the visible topic entries'
fi

printf '\nRESULT %s (%d failed / %d checks)\n' \
    "$([ "$failures" -eq 0 ] && printf 'PASS' || printf 'FAIL')" \
    "$failures" \
    "$checks"

if [ "$failures" -ne 0 ]; then
    exit 1
fi
