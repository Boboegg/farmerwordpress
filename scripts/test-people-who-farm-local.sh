#!/usr/bin/env bash
set -euo pipefail

mode="${1:-all}"
PHP="${PHP:-$HOME/Library/Application Support/Local/lightning-services/php-8.3.30+1/bin/darwin-arm64/bin/php}"
WP_CLI="${WP_CLI:-/Applications/Local.app/Contents/Resources/extraResources/bin/wp-cli/wp-cli.phar}"
WP_PATH="${WP_PATH:-$HOME/Local Sites/fwdignity-local/app/public}"
BASE_URL="${BASE_URL:-https://fwdignity-local.local}"
MYSQL_BIN="${MYSQL_BIN:-$HOME/Library/Application Support/Local/lightning-services/mysql-8.0.35+4/bin/darwin-arm64/bin}"
MYSQL_UNIX_PORT="${MYSQL_UNIX_PORT:-$HOME/Library/Application Support/Local/run/FdpHP5Axi/mysql/mysqld.sock}"
THEME_DIR="${THEME_DIR:-$WP_PATH/wp-content/themes/fwdignity}"
BASELINE_HASHES="${BASELINE_HASHES:-$HOME/fwdignity-local-backups/2026-07-12-pre-people-who-farm/publish-post-content.sha256}"

export PATH="$MYSQL_BIN:$PATH"
export MYSQL_UNIX_PORT

fail() {
    printf '%s\n' "$1" >&2
    exit 1
}

wp() {
    "$PHP" \
        -d "mysqli.default_socket=$MYSQL_UNIX_PORT" \
        -d "pdo_mysql.default_socket=$MYSQL_UNIX_PORT" \
        "$WP_CLI" --path="$WP_PATH" "$@"
}

test_data() {
    wp eval '
        if ( ! taxonomy_exists( "fwd_topic" ) ) {
            fwrite( STDERR, "fwd_topic taxonomy is missing\n" );
            exit( 1 );
        }

        $taxonomy = get_taxonomy( "fwd_topic" );
        if ( ! $taxonomy || $taxonomy->public || $taxonomy->publicly_queryable || false !== $taxonomy->rewrite ) {
            fwrite( STDERR, "fwd_topic must be internal and have no rewrite\n" );
            exit( 1 );
        }

        $required_functions = array(
            "fwdignity_topic_registry",
            "fwdignity_topic_term_slug_for_page",
            "fwdignity_get_topic_page_url",
            "fwdignity_topic_query",
            "fwdignity_topic_card_labels",
        );
        foreach ( $required_functions as $function ) {
            if ( ! function_exists( $function ) ) {
                fwrite( STDERR, "topic helper is missing: {$function}\n" );
                exit( 1 );
            }
        }

        $registry = fwdignity_topic_registry();
        if ( 5 !== count( $registry ) ) {
            fwrite( STDERR, "expected five topic registry entries\n" );
            exit( 1 );
        }
        foreach ( $registry as $topic ) {
            if ( ! term_exists( $topic["term_slug"], "fwd_topic" ) ) {
                fwrite( STDERR, "topic term is missing: {$topic["term_slug"]}\n" );
                exit( 1 );
            }
        }

        $expected_assignments = array(
            1029 => array( "knowledge-to-field", "rural-support" ),
            1297 => array( "body-workload", "risk-responsibility" ),
            1469 => array( "body-workload", "changing-work" ),
            1499 => array( "body-workload", "knowledge-to-field", "risk-responsibility" ),
            1609 => array( "body-workload", "knowledge-to-field" ),
            1626 => array( "knowledge-to-field", "rural-support" ),
            1633 => array( "body-workload", "changing-work", "risk-responsibility", "rural-support" ),
            1646 => array( "body-workload", "changing-work", "risk-responsibility" ),
            1829 => array( "body-workload", "knowledge-to-field", "rural-support" ),
        );
        foreach ( $expected_assignments as $post_id => $expected_slugs ) {
            $actual_slugs = wp_get_object_terms( $post_id, "fwd_topic", array( "fields" => "slugs" ) );
            sort( $actual_slugs );
            sort( $expected_slugs );
            if ( $actual_slugs !== $expected_slugs ) {
                fwrite( STDERR, "topic assignments differ for post {$post_id}\n" );
                exit( 1 );
            }
        }

        if ( ! registered_meta_key_exists( "post", "_fwd_topic_featured_post_id", "page" ) ) {
            fwrite( STDERR, "topic Page featured-post meta is missing\n" );
            exit( 1 );
        }

        echo "people-who-farm data checks passed\n";
    '
}

test_pages() {
    wp eval '
        $hub = get_page_by_path( "people-who-farm", OBJECT, "page" );
        if ( ! $hub || "publish" !== $hub->post_status ) {
            fwrite( STDERR, "people-who-farm hub Page is missing\n" );
            exit( 1 );
        }
        foreach ( fwdignity_topic_registry() as $topic ) {
            $page = get_page_by_path( "people-who-farm/" . $topic["page_slug"], OBJECT, "page" );
            if ( ! $page || "publish" !== $page->post_status || (int) $hub->ID !== (int) $page->post_parent ) {
                fwrite( STDERR, "topic child Page is missing or has wrong parent: {$topic["page_slug"]}\n" );
                exit( 1 );
            }
        }
        $contact_pages = get_posts(
            array(
                "post_type"      => "page",
                "post_status"    => array( "publish", "draft", "private", "pending", "future" ),
                "name"           => "contact",
                "posts_per_page" => -1,
            )
        );
        foreach ( $contact_pages as $contact_page ) {
            if ( "draft" !== $contact_page->post_status ) {
                fwrite( STDERR, "standalone contact Page must remain draft\n" );
                exit( 1 );
            }
        }
        echo "people-who-farm Page data checks passed\n";
    '

    local hub_html
    hub_html="$(curl --silent --show-error --fail --insecure --location "$BASE_URL/people-who-farm/")"
    grep -Fq 'class="pf-hub"' <<<"$hub_html" || fail 'people-who-farm hub template is missing'
    grep -Fq 'data-topic-map' <<<"$hub_html" || fail 'v3 topic map is missing'
    grep -Fq '最近更新' <<<"$hub_html" || fail 'recent topic updates are missing'
    grep -Fq '>認識尊嚴農業<' <<<"$hub_html" || fail 'approved About navigation label is missing'
    grep -Fq '>做農的人<' <<<"$hub_html" || fail 'approved People Who Farm footer link is missing'
    if grep -Fq '/about/contact/' <<<"$hub_html"; then
        fail 'standalone contact Page is still linked from the shared shell'
    fi

    for path in body-and-farm-work changing-farm-work rural-support risk-and-responsibility knowledge-in-the-field; do
        local html
        html="$(curl --silent --show-error --fail --insecure --location "$BASE_URL/people-who-farm/$path/")"
        grep -Fq 'class="pf-topic-page ' <<<"$html" || fail "topic template is missing: $path"
        if grep -Fq 'class="pf-topic-navigation"' <<<"$html"; then
            fail "retired five-option topic navigation is still rendered: $path"
        fi
    done

    if curl --silent --show-error --insecure --output /dev/null --write-out '%{http_code}' "$BASE_URL/fwd-topic/body-workload/" | grep -Fq '200'; then
        fail 'internal topic taxonomy unexpectedly has a public archive'
    fi

    printf 'people-who-farm HTTP checks passed\n'
}

test_assets() {
    local css="$THEME_DIR/assets/css/page-people-who-farm.css"
    local js="$THEME_DIR/assets/js/page-people-who-farm.js"

    [ -f "$css" ] || fail "people-who-farm CSS is missing: $css"
    [ -f "$js" ] || fail "people-who-farm JavaScript is missing: $js"

    for selector in '.pf-hub' '.pf-topic-desk' '.pf-topic-page' '.pf-content-list'; do
        grep -Fq "$selector" "$css" || fail "missing people-who-farm CSS selector: $selector"
    done

    grep -Fq 'data-topic-entry' "$js" || fail 'topic map enhancement contract is missing'
    if grep -Fq "addEventListener( 'click'" "$js"; then
        fail 'topic map JavaScript must not intercept navigation clicks'
    fi
    if grep -Eq 'setInterval|setTimeout' "$js"; then
        fail 'topic switcher must not auto-advance'
    fi

    "$PHP" -r '
        $css = file_get_contents( $argv[1] );
        if ( substr_count( $css, "{" ) !== substr_count( $css, "}" ) ) {
            fwrite( STDERR, "people-who-farm CSS braces are unbalanced\n" );
            exit( 1 );
        }
    ' "$css"
    node --check "$js"
    printf 'people-who-farm asset checks passed\n'
}

test_news_filters() {
    local body_html
    local combined_html
    local cross_facet_html

    body_html="$(curl --silent --show-error --fail --insecure --location "$BASE_URL/thenews/?topic%5B%5D=body-workload")"
    grep -Fq '找到 7 篇內容' <<<"$body_html" || fail 'body-workload news filter returned the wrong count'
    grep -Fq '身體如何承受農務？' <<<"$body_html" || fail 'body-workload active filter label is missing'

    combined_html="$(curl --silent --show-error --fail --insecure --location "$BASE_URL/thenews/?topic%5B%5D=changing-work&topic%5B%5D=rural-support")"
    grep -Fq '找到 7 篇內容' <<<"$combined_html" || fail 'same-facet people topics must use OR'
    grep -Fq '農業工作正在怎麼改變？' <<<"$combined_html" || fail 'changing-work active filter label is missing'
    grep -Fq '誰在撐起農村？' <<<"$combined_html" || fail 'rural-support active filter label is missing'

    cross_facet_html="$(curl --silent --show-error --fail --insecure --location "$BASE_URL/thenews/?topic%5B%5D=rural-support&format%5B%5D=podcast")"
    grep -Fq '找到 1 篇內容' <<<"$cross_facet_html" || fail 'people topic and format facets must use AND'

    printf 'people-who-farm latest-news filter checks passed\n'
}

test_hashes() {
    [ -f "$BASELINE_HASHES" ] || fail "baseline post hashes are missing: $BASELINE_HASHES"

    local current
    local baseline_ids
    current="$(mktemp)"
    trap 'rm -f "$current"' RETURN
    baseline_ids="$(cut -f1 "$BASELINE_HASHES" | paste -sd, -)"
    FWD_BASELINE_IDS="$baseline_ids" wp eval '
        $ids = array_map( "intval", explode( ",", (string) getenv( "FWD_BASELINE_IDS" ) ) );
        foreach ( $ids as $post_id ) {
            $post = get_post( $post_id );
            $expected_status = 1469 === $post_id ? "trash" : "publish";
            if ( ! $post || $expected_status !== $post->post_status ) {
                fwrite( STDERR, "baseline post status differs: {$post_id}\n" );
                exit( 1 );
            }
            echo $post->ID."\t".hash("sha256", $post->post_content)."\n";
        }
    ' > "$current"
    cmp "$BASELINE_HASHES" "$current" || fail 'the nine protected legacy post bodies changed'
    printf 'the protected legacy corpus has the approved statuses and unchanged bodies\n'
}

case "$mode" in
    --data-only)
        test_data
        ;;
    --pages-only)
        test_pages
        ;;
    --assets-only)
        test_assets
        ;;
    --hashes-only)
        test_hashes
        ;;
    --filters-only)
        test_news_filters
        ;;
    all)
        test_data
        test_pages
        test_assets
        test_news_filters
        test_hashes
        ;;
    *)
        fail "unknown mode: $mode"
        ;;
esac

printf 'people-who-farm LocalWP smoke test passed: %s\n' "$mode"
