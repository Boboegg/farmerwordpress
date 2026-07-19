#!/usr/bin/env bash
set -euo pipefail

PHP="${PHP:-$HOME/Library/Application Support/Local/lightning-services/php-8.3.30+1/bin/darwin-arm64/bin/php}"
WP_CLI="${WP_CLI:-/Applications/Local.app/Contents/Resources/extraResources/bin/wp-cli/wp-cli.phar}"
WP_PATH="${WP_PATH:-$HOME/Local Sites/fwdignity-local/app/public}"
BASE_URL="${BASE_URL:-https://fwdignity-local.local}"
MYSQL_UNIX_PORT="${MYSQL_UNIX_PORT:-$HOME/Library/Application Support/Local/run/FdpHP5Axi/mysql/mysqld.sock}"
THEME_DIR="${THEME_DIR:-$WP_PATH/wp-content/themes/fwdignity}"
BASELINE_HASHES="${BASELINE_HASHES:-$HOME/fwdignity-local-backups/2026-07-12-pre-people-who-farm/publish-post-content.sha256}"

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

wp eval '
    $expected = array(
        "research-results" => "研究與行動",
        "about"            => "認識尊嚴農業",
    );
    foreach ( $expected as $slug => $title ) {
        $page = get_page_by_path( $slug, OBJECT, "page" );
        if ( ! $page || "publish" !== $page->post_status || $title !== $page->post_title ) {
            fwrite( STDERR, "landing Page title or status is wrong: {$slug}\n" );
            exit( 1 );
        }
    }
    echo "research/about Page data checks passed\n";
'

research_html="$(curl --silent --show-error --fail --insecure --location "$BASE_URL/research-results/")"
about_html="$(curl --silent --show-error --fail --insecure --location "$BASE_URL/about/")"

grep -Fq 'class="ra-page"' <<<"$research_html" || fail 'research-action landing template is missing'
grep -Fq '研究從問題開始' <<<"$research_html" || fail 'research-to-topic bridge is missing'
grep -Fq '行動如何發生' <<<"$research_html" || fail 'research-to-action bridge is missing'
grep -Fq '公開成果與資源' <<<"$research_html" || fail 'research output section is missing'
grep -Fq 'data-research-topic' <<<"$research_html" || fail 'five research topic links are missing'
[ "$(grep -Fo 'data-research-topic' <<<"$research_html" | wc -l | tr -d ' ')" = '5' ] || fail 'research landing must render five topic links'

grep -Fq 'class="fw-nav-item has-dropdown has-landing-link"' <<<"$research_html" || fail 'landing-link navigation groups are missing'
grep -Eq 'href="[^"]*/research-results/" class="fw-nav-link">研究與行動</a>' <<<"$research_html" || fail 'research landing link is not directly clickable'
grep -Eq 'href="[^"]*/about/" class="fw-nav-link">認識尊嚴農業</a>' <<<"$about_html" || fail 'about landing link is not directly clickable'

for path in research-results research-results/research-publications research-results/related-resources about about/researchteam; do
    html="$(curl --silent --show-error --fail --insecure --location "$BASE_URL/$path/")"
    grep -Fq 'page-research-about-refresh.css' <<<"$html" || fail "refresh stylesheet is missing: $path"
done

[ "$(curl --silent --show-error --insecure --location --output /dev/null --write-out '%{http_code}' "$BASE_URL/dignity-farming-initiative/")" = '404' ] \
    || fail 'hidden advocacy Page must return 404'

css="$THEME_DIR/assets/css/page-research-about-refresh.css"
[ -f "$css" ] || fail 'research/about refresh CSS is missing'
for token in '--fw-color-page' '--fw-color-surface' '--fw-color-ink' '--fw-color-primary' '--fw-color-link' '--fw-color-accent' '--fw-color-line'; do
    grep -Fq -- "$token" "$css" || fail "Clear Field token is missing: $token"
done
for retired in '#EEE9D5' '#FBF3E6' '#B8913C' '#4E8092' '#D6CEB7'; do
    if grep -Fiq -- "$retired" "$css"; then
        fail "retired color is still present: $retired"
    fi
done

current_hashes="$(mktemp)"
trap 'rm -f "$current_hashes"' EXIT
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
' > "$current_hashes"
cmp "$BASELINE_HASHES" "$current_hashes" || fail 'the nine protected legacy post bodies changed'

printf 'research/about LocalWP smoke test passed\n'
