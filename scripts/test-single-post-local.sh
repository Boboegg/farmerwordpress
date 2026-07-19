#!/usr/bin/env bash
set -euo pipefail

PHP_BIN="${PHP_BIN:-$HOME/Library/Application Support/Local/lightning-services/php-8.3.30+1/bin/darwin-arm64/bin/php}"
WP_CLI="${WP_CLI:-/Applications/Local.app/Contents/Resources/extraResources/bin/wp-cli/wp-cli.phar}"
WP_ROOT="${WP_ROOT:-$HOME/Local Sites/fwdignity-local/app/public}"
SOCKET="${SOCKET:-$HOME/Library/Application Support/Local/run/FdpHP5Axi/mysql/mysqld.sock}"
BASE_URL="${BASE_URL:-https://fwdignity-local.local}"

wp_eval() {
    "$PHP_BIN" -d "mysqli.default_socket=$SOCKET" -d "pdo_mysql.default_socket=$SOCKET" \
        "$WP_CLI" --path="$WP_ROOT" eval "$1"
}

hashes_before="$(wp_eval 'foreach ( get_posts( array( "post_type" => "post", "post_status" => "publish", "posts_per_page" => -1, "orderby" => "ID", "order" => "ASC" ) ) as $post ) { echo $post->ID . ":" . hash( "sha256", $post->post_content ) . PHP_EOL; }')"
permalinks="$(wp_eval 'foreach ( get_posts( array( "post_type" => "post", "post_status" => "publish", "posts_per_page" => -1, "orderby" => "ID", "order" => "ASC" ) ) as $post ) { echo get_permalink( $post ) . PHP_EOL; }')"

count=0
while IFS= read -r permalink; do
    [ -n "$permalink" ] || continue
    body="$(curl -k -sS "$permalink")"
    grep -q 'class="article-shell"' <<< "$body"
    grep -Eq "id=['\"]fwdignity-single-post-css['\"]" <<< "$body"
    grep -q 'class="article-content"' <<< "$body"
    featured_markup="$(perl -0777 -ne 'if (/<figure class="article-featured-image">(.*?)<\/figure>/s) { print $1 }' <<< "$body")"
    if [ -n "$featured_markup" ] && grep -q 'alt=""' <<< "$featured_markup"; then
        printf 'Featured image has empty alt text: %s\n' "$permalink" >&2
        exit 1
    fi
    count=$((count + 1))
done <<< "$permalinks"

hashes_after="$(wp_eval 'foreach ( get_posts( array( "post_type" => "post", "post_status" => "publish", "posts_per_page" => -1, "orderby" => "ID", "order" => "ASC" ) ) as $post ) { echo $post->ID . ":" . hash( "sha256", $post->post_content ) . PHP_EOL; }')"

if [ "$hashes_before" != "$hashes_after" ]; then
    printf '%s\n' 'Published post_content changed during single-page checks.' >&2
    exit 1
fi

printf 'single post checks passed: %d posts at %s\n' "$count" "$BASE_URL"
