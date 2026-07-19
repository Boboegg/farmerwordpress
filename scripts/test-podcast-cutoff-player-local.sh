#!/usr/bin/env bash
set -euo pipefail

PHP_BIN="${PHP_BIN:-$HOME/Library/Application Support/Local/lightning-services/php-8.3.30+1/bin/darwin-arm64/bin/php}"
WP_CLI="${WP_CLI:-/Applications/Local.app/Contents/Resources/extraResources/bin/wp-cli/wp-cli.phar}"
WP_ROOT="${WP_ROOT:-$HOME/Local Sites/fwdignity-local/app/public}"
SOCKET="${SOCKET:-$HOME/Library/Application Support/Local/run/FdpHP5Axi/mysql/mysqld.sock}"
BASE_URL="${BASE_URL:-https://fwdignity-local.local}"
THEME_DIR="${THEME_DIR:-$WP_ROOT/wp-content/themes/fwdignity}"

fail() {
    printf '%s\n' "$1" >&2
    exit 1
}

wp_eval() {
    "$PHP_BIN" \
        -d "mysqli.default_socket=$SOCKET" \
        -d "pdo_mysql.default_socket=$SOCKET" \
        "$WP_CLI" --path="$WP_ROOT" eval "$1"
}

expected_episodes='EP168,EP182,EP183,EP193,EP194,EP195,EP196,EP197,EP206,EP207,EP208,EP209,EP210,EP211,EP218'
actual_episodes="$(wp_eval '
    $query = new WP_Query(
        array(
            "post_type"      => "post",
            "post_status"    => "publish",
            "posts_per_page" => -1,
            "category_name"  => "podcast",
        )
    );
    $episodes = array();
    foreach ( $query->posts as $post ) {
        $episode = get_post_meta( $post->ID, "_fwd_media_episode_number", true );
        if ( $episode ) {
            $episodes[] = $episode;
        }
    }
    natsort( $episodes );
    echo implode( ",", $episodes );
')"

[ "$actual_episodes" = "$expected_episodes" ] || fail "Podcast 保留清單錯誤：$actual_episodes"

non_audio_episodes="$(wp_eval '
    $posts = get_posts(
        array(
            "post_type"      => "post",
            "post_status"    => "publish",
            "posts_per_page" => -1,
            "category_name"  => "podcast",
        )
    );
    $non_audio = array();
    foreach ( $posts as $post ) {
        $item = fwdignity_get_media_item( $post->ID );
        if ( "audio" !== $item["embed"]["type"] || empty( $item["embed"]["url"] ) ) {
            $non_audio[] = $item["episode"];
        }
    }
    natsort( $non_audio );
    echo implode( ",", $non_audio );
')"
[ -z "$non_audio_episodes" ] || fail "Podcast 未全數使用站內音訊播放器：$non_audio_episodes"

hub_html="$(curl --silent --show-error --fail --insecure --location "$BASE_URL/podcast/")"
single_url="$(wp_eval '
    $posts = get_posts(
        array(
            "post_type"      => "post",
            "post_status"    => "publish",
            "posts_per_page" => 1,
            "meta_key"       => "_fwd_media_episode_number",
            "meta_value"     => "EP218",
        )
    );
    echo $posts ? get_permalink( $posts[0] ) : "";
')"
[ -n "$single_url" ] || fail '找不到 EP218 單集頁'
single_html="$(curl --silent --show-error --fail --insecure --location "$single_url")"

for html_name in hub_html single_html; do
    html="${!html_name}"
    grep -Fq 'data-fw-audio-player' <<<"$html" || fail "$html_name 缺少 Podcast 播放器"
    grep -Fq 'data-fw-audio-toggle' <<<"$html" || fail "$html_name 缺少播放／暫停控制"
    grep -Fq 'data-fw-audio-seek' <<<"$html" || fail "$html_name 缺少播放進度控制"
    grep -Fq 'data-fw-audio-current' <<<"$html" || fail "$html_name 缺少目前時間"
    grep -Fq 'data-fw-audio-duration' <<<"$html" || fail "$html_name 缺少總長度"
done

grep -Fq '.fw-audio-player' "$THEME_DIR/assets/css/page-media-hub.css" || fail '播放器 CSS 不存在'
grep -Fq 'initAudioPlayers' "$THEME_DIR/assets/js/page-media-hub.js" || fail '播放器 JavaScript 不存在'
# 2026-07-17 W2-C：快取戳記改由 fwdignity_bust_asset_version() 輸出端統一處理。
grep -Fq "add_filter( 'style_loader_src', 'fwdignity_bust_asset_version' )" "$THEME_DIR/functions.php" || fail 'Podcast CSS 缺少快取版本更新'
grep -Fq "add_filter( 'script_loader_src', 'fwdignity_bust_asset_version' )" "$THEME_DIR/functions.php" || fail 'Podcast JavaScript 缺少快取版本更新'

node --check "$THEME_DIR/assets/js/page-media-hub.js"
"$PHP_BIN" -l "$THEME_DIR/page-podcast.php" >/dev/null
"$PHP_BIN" -l "$THEME_DIR/single.php" >/dev/null
"$PHP_BIN" -l "$THEME_DIR/inc/media-hub.php" >/dev/null

printf 'Podcast 日期與播放器驗收通過：15 集，首頁與單集頁播放器完整\n'
