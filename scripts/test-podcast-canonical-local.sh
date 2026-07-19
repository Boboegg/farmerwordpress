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

state="$(wp_eval '$page=get_post(1343); echo $page->post_parent . "|" . get_permalink($page) . "|" . hash("sha256", $page->post_content);')"
parent="${state%%|*}"
rest="${state#*|}"
permalink="${rest%%|*}"

[ "$parent" = "0" ]
[ "$permalink" = "$BASE_URL/podcast/" ]

direct="$(curl -k -sS -o /dev/null -w '%{http_code}|%{redirect_url}' "$BASE_URL/podcast/")"
legacy="$(curl -k -sS -o /dev/null -w '%{http_code}|%{redirect_url}' "$BASE_URL/research-results/podcast/")"

[ "$direct" = "200|" ]
[ "$legacy" = "301|$BASE_URL/podcast/" ]

printf 'podcast canonical checks passed: %s; legacy %s\n' "$direct" "$legacy"
