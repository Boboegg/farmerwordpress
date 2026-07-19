#!/usr/bin/env bash
set -euo pipefail

PHP_BIN="${PHP_BIN:-$HOME/Library/Application Support/Local/lightning-services/php-8.3.30+1/bin/darwin-arm64/bin/php}"
WP_CLI="${WP_CLI:-/Applications/Local.app/Contents/Resources/extraResources/bin/wp-cli/wp-cli.phar}"
WP_ROOT="${WP_ROOT:-$HOME/Local Sites/fwdignity-local/app/public}"
SOCKET="${SOCKET:-$HOME/Library/Application Support/Local/run/FdpHP5Axi/mysql/mysqld.sock}"
BASE_URL="${BASE_URL:-https://fwdignity-local.local}"

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

actual_dates="$(wp_eval '
    $posts = get_posts(
        array(
            "post_type"      => "post",
            "post_status"    => "publish",
            "posts_per_page" => -1,
            "meta_key"       => "_fwd_facebook_source_url",
            "orderby"        => "ID",
            "order"          => "ASC",
        )
    );
    $dates = array();
    foreach ( $posts as $post ) {
        $dates[] = $post->ID . ":" . get_the_date( "Y-m-d H:i:s", $post );
    }
    echo implode( ",", $dates );
')"

expected_dates='2193:2026-06-05 00:00:00,2196:2026-05-12 00:00:00,2199:2026-06-24 13:39:00,2202:2026-06-26 11:31:00'
[ "$actual_dates" = "$expected_dates" ] || fail "Facebook 來源日期不一致：$actual_dates"

field_date_mismatches="$(wp_eval '
    $posts = get_posts(
        array(
            "post_type"      => "post",
            "post_status"    => "publish",
            "posts_per_page" => -1,
            "meta_key"       => "_fwd_facebook_source_url",
        )
    );
    $mismatches = array();
    foreach ( $posts as $post ) {
        $field_date = get_post_meta( $post->ID, "_fwdignity_field_date", true );
        if ( $field_date !== get_the_date( "Y-m-d", $post ) ) {
            $mismatches[] = $post->ID;
        }
    }
    echo implode( ",", $mismatches );
')"
[ -z "$field_date_mismatches" ] || fail "文章日期與來源欄位不一致：$field_date_mismatches"

index_html="$(curl --silent --show-error --fail --insecure --location "$BASE_URL/thenews/")"
grep -Fq '2026.06.26' <<<"$index_html" || fail '全部內容頁未顯示 2026.06.26'
grep -Fq '2026.06.24' <<<"$index_html" || fail '全部內容頁未顯示 2026.06.24'
grep -Fq '2026.06.05' <<<"$index_html" || fail '全部內容頁未顯示 2026.06.05'
grep -Fq '2026.05.12' <<<"$index_html" || fail '全部內容頁未顯示 2026.05.12'

printf 'Facebook 來源日期驗收通過：4 篇文章均依原始貼文日期顯示\n'
