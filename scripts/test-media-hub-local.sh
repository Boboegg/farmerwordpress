#!/usr/bin/env bash
set -euo pipefail

mode="${1:-all}"
PHP="${PHP:-$HOME/Library/Application Support/Local/lightning-services/php-8.3.30+1/bin/darwin-arm64/bin/php}"
WP_CLI="${WP_CLI:-/Applications/Local.app/Contents/Resources/extraResources/bin/wp-cli/wp-cli.phar}"
WP_PATH="${WP_PATH:-$HOME/Local Sites/fwdignity-local/app/public}"
BASE_URL="${BASE_URL:-https://fwdignity-local.local}"
MYSQL_BIN="${MYSQL_BIN:-$HOME/Library/Application Support/Local/lightning-services/mysql-8.4.0/bin/darwin-arm64/bin}"
MYSQL_UNIX_PORT="${MYSQL_UNIX_PORT:-$HOME/Library/Application Support/Local/run/FdpHP5Axi/mysql/mysqld.sock}"
THEME_DIR="${THEME_DIR:-$WP_PATH/wp-content/themes/fwdignity}"

export PATH="$MYSQL_BIN:$PATH"
export MYSQL_UNIX_PORT

fail() {
    printf '%s\n' "$1" >&2
    exit 1
}

wp_eval() {
    wp eval "$1"
}

wp() {
    "$PHP" \
        -d "mysqli.default_socket=$MYSQL_UNIX_PORT" \
        -d "pdo_mysql.default_socket=$MYSQL_UNIX_PORT" \
        "$WP_CLI" --path="$WP_PATH" "$@"
}

test_data() {
    wp_eval '
        if ( ! taxonomy_exists( "media_series" ) ) {
            fwrite( STDERR, "media_series taxonomy is missing\n" );
            exit( 1 );
        }
        foreach ( array( "_fwd_media_embed_url", "_fwd_media_audio_url", "_fwd_media_episode_number", "_fwd_media_duration", "_fwd_media_key_points" ) as $key ) {
            if ( ! registered_meta_key_exists( "post", $key, "post" ) ) {
                fwrite( STDERR, "registered media meta is missing: {$key}\n" );
                exit( 1 );
            }
        }
        if ( ! function_exists( "fwdignity_get_media_items" ) ) {
            fwrite( STDERR, "media query helper is missing\n" );
            exit( 1 );
        }
        $items = fwdignity_get_media_items( array( "posts_per_page" => -1 ) );
        $ids   = array_map( "intval", wp_list_pluck( $items, "id" ) );
        if ( count( $ids ) !== count( array_unique( $ids ) ) ) {
            fwrite( STDERR, "media query returned duplicate post IDs\n" );
            exit( 1 );
        }
        if ( 17 !== count( $ids ) ) {
            fwrite( STDERR, "expected 17 unique LocalWP media posts, got " . count( $ids ) . "\n" );
            exit( 1 );
        }
        echo "media data checks passed\n";
    '
}

test_page() {
    local html podcast_html video_html
    html="$(curl --silent --show-error --fail --insecure --location "$BASE_URL/podcast/")"

    for label in '聽見田裡的聲音' '本期精選' '節目系列' '最新 Podcast' '最新影片' '全部聲音與影像'; do
        grep -Fq "$label" <<<"$html" || fail "missing section: $label"
    done

    grep -Fq 'class="mh-main"' <<<"$html" || fail 'media hub root is missing'
    grep -Fq 'class="mh-feature-stage' <<<"$html" || fail 'featured stage is missing'
    grep -Fq 'class="mh-archive-list' <<<"$html" || fail 'media archive list is missing'

    if grep -Fq 'template-parts/sidebar' <<<"$html" || grep -Fq 'aria-label="側邊工具"' <<<"$html"; then
        fail 'legacy media sidebar is still rendered'
    fi

    for legacy in 'Apple Podcast' 'YouTube Music' 'SoundOn' 'KKBOX' '節目主題規劃' '參與合作'; do
        if grep -Fq "$legacy" <<<"$html"; then
            fail "legacy podcast block is still rendered: $legacy"
        fi
    done

    podcast_html="$(curl --silent --show-error --fail --insecure --location "$BASE_URL/podcast/?media_format=podcast")"
    grep -Fq '15 篇內容' <<<"$podcast_html" || fail 'Podcast format filter did not return 15 posts'

    video_html="$(curl --silent --show-error --fail --insecure --location "$BASE_URL/podcast/?media_format=video")"
    grep -Fq '2 篇內容' <<<"$video_html" || fail 'video format filter did not merge YouTube and video categories'

    printf 'media hub page checks passed\n'
}

test_series() {
    local slug html
    slug="$(wp term list media_series --field=slug --number=1 2>/dev/null | head -1)"
    [ -n "$slug" ] || fail 'no media_series term exists for archive test'

    html="$(curl --silent --show-error --fail --insecure --location "$BASE_URL/media-series/$slug/")"
    grep -Fq 'class="mh-series-archive' <<<"$html" || fail 'series archive template is missing'
    grep -Fq '全部集數' <<<"$html" || fail 'series archive episode list is missing'
    grep -Fq '返回聽見田裡的聲音' <<<"$html" || fail 'series archive return link is missing'
    printf 'media series archive checks passed: %s\n' "$slug"
}

test_assets() {
    local css="$THEME_DIR/assets/css/page-media-hub.css"
    local js="$THEME_DIR/assets/js/page-media-hub.js"

    [ -f "$css" ] || fail "media hub CSS is missing: $css"
    [ -f "$js" ] || fail "media hub JavaScript is missing: $js"

    for selector in '.mh-feature-stage' '.mh-podcast-desk' '.mh-video-screen' '.mh-archive-list' '.mh-series-archive' '.fw-audio-player' '.fw-audio-player__timeline'; do
        grep -Fq "$selector" "$css" || fail "missing CSS selector: $selector"
    done

    "$PHP" -r '
        $css = file_get_contents( $argv[1] );
        if ( substr_count( $css, "{" ) !== substr_count( $css, "}" ) ) {
            fwrite( STDERR, "media hub CSS braces are unbalanced\n" );
            exit( 1 );
        }
    ' "$css"

    grep -Fq 'FEATURE_INTERVAL = 7000' "$js" || fail 'media hub JavaScript contract is missing: interval'
    grep -Fq 'prefers-reduced-motion' "$js" || fail 'media hub JavaScript contract is missing: reduced motion'
    grep -Fq 'data-media-play' "$js" || fail 'media hub JavaScript contract is missing: player'
    grep -Fq 'data-fw-audio-player' "$js" || fail 'media hub JavaScript contract is missing: custom audio player'
    grep -Fq 'data-media-podcast-item' "$js" || fail 'media hub JavaScript contract is missing: podcast queue'
    grep -Fq 'data-media-video-item' "$js" || fail 'media hub JavaScript contract is missing: video selector'

    node --check "$js"
    printf 'media hub asset checks passed\n'
}

case "$mode" in
    --data-only)
        test_data
        ;;
    --page-only)
        test_page
        ;;
    --series-only)
        test_series
        ;;
    --assets-only)
        test_assets
        ;;
    all)
        test_data
        test_page
        test_series
        test_assets
        ;;
    *)
        fail "unknown mode: $mode"
        ;;
esac

printf 'media hub LocalWP smoke test passed: %s\n' "$mode"
