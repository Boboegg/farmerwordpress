#!/usr/bin/env bash
set -euo pipefail

PHP="${PHP:-$HOME/Library/Application Support/Local/lightning-services/php-8.3.30+1/bin/darwin-arm64/bin/php}"
WP_CLI="${WP_CLI:-/Applications/Local.app/Contents/Resources/extraResources/bin/wp-cli/wp-cli.phar}"
WP_PATH="${WP_PATH:-$HOME/Local Sites/fwdignity-local/app/public}"
BASE_URL="${BASE_URL:-https://fwdignity-local.local}"
MYSQL_UNIX_PORT="${MYSQL_UNIX_PORT:-$HOME/Library/Application Support/Local/run/FdpHP5Axi/mysql/mysqld.sock}"
THEME_DIR="${THEME_DIR:-$WP_PATH/wp-content/themes/fwdignity}"

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

tokens="$THEME_DIR/assets/css/site-v2-tokens.css"
layout="$THEME_DIR/assets/css/layout.css"
parts="$THEME_DIR/assets/css/site-parts.css"
page_foundation="$THEME_DIR/assets/css/site-v2-page-foundation.css"
functions="$THEME_DIR/functions.php"
header="$THEME_DIR/header.php"
footer="$THEME_DIR/footer.php"

for file in "$tokens" "$layout" "$parts" "$page_foundation" "$functions" "$header" "$footer"; do
    [ -f "$file" ] || fail "Required global foundation file is missing: $file"
done

grep -Fq -- '--fw-color-page: #FDFBF7;' "$tokens" || fail 'Canonical page token is missing.'
grep -Fq -- '--fw-color-primary: #435832;' "$tokens" || fail 'Canonical primary token is missing.'
grep -Fq -- '--fw-color-media-background: #F5F5F2;' "$tokens" || fail 'Canonical light media background token is missing.'
grep -Fq -- '--fw-home-media-band: #435832;' "$tokens" || fail 'Approved home-only media band token is missing.'
grep -Fq -- '--fw-font-size-body: 18px;' "$tokens" || fail 'Canonical 18px general body token is missing.'
grep -Fq -- '--fw-font-size-long-body: 20px;' "$tokens" || fail 'Canonical 20px long-reading token is missing.'
grep -Fq -- '--fw-font-size-nav: 17px;' "$tokens" || fail 'Canonical 17px navigation token is missing.'
grep -Fq -- '--fw-font-size-meta: 15px;' "$tokens" || fail 'Canonical 15px metadata token is missing.'

tokens_line="$(grep -n "'fwdignity-v2-tokens'" "$functions" | head -1 | cut -d: -f1)"
layout_line="$(grep -n "'fwdignity-layout'" "$functions" | head -1 | cut -d: -f1)"
page_foundation_line="$(grep -n "'fwdignity-v2-page-foundation'" "$functions" | head -1 | cut -d: -f1)"
[ -n "$tokens_line" ] || fail 'Token stylesheet is not enqueued.'
[ -n "$layout_line" ] || fail 'Layout stylesheet enqueue is missing.'
[ -n "$page_foundation_line" ] || fail 'Page-family foundation stylesheet is not enqueued.'
[ "$tokens_line" -lt "$layout_line" ] || fail 'Token stylesheet must load before layout styles.'
[ "$layout_line" -lt "$page_foundation_line" ] || fail 'Page-family foundation must load after layout styles.'
if grep -Fq "'fwdignity-v15-master'" "$functions" || grep -Eq 'v15-master\.(css|js)' "$functions"; then
    fail 'Retired V15 assets are still enqueued.'
fi

grep -Fq 'font-size: var(--fw-font-size-body);' "$layout" || fail 'Global body does not use the canonical general-body token.'
grep -Fq 'font-size: var(--fw-font-size-nav);' "$layout" || fail 'Global navigation does not use the 17px token.'
grep -Fq 'font-size: var(--fw-font-size-meta);' "$layout" || fail 'Global metadata does not use the 15px token.'
grep -Fq 'font-size: var(--fw-font-size-h1) !important;' "$page_foundation" || fail 'Page-family H1 is not locked to the canonical token.'
grep -Fq 'font-size: var(--fw-font-size-summary) !important;' "$page_foundation" || fail 'Page-family summary text is not locked to the canonical token.'
if grep -Eq 'transition:[[:space:]]*all' "$layout" "$parts"; then
    fail 'Global styles still use transition: all.'
fi

grep -Fq "home_url( '/' )" "$header" || fail 'Header home link is missing.'
grep -Fq '>首頁</a>' "$header" || fail 'Visible 首頁 navigation item is missing.'
grep -Fq '>聽見田裡的聲音</a>' "$header" || fail 'Final media hub navigation label is missing.'
if grep -Fq '了解倡議' "$header"; then
    fail 'Removed advocacy CTA is still present in the header.'
fi
if grep -Fq '>關於尊嚴農業</a>' "$header"; then
    fail 'About dropdown still repeats its landing page.'
fi
grep -Fq 'fw-footer-brand' "$footer" || fail 'Compact footer brand column is missing.'
grep -Fq 'id="footer-contact"' "$footer" || fail 'Compact footer contact column is missing.'
if grep -Fq '>網站導覽<' "$footer" || grep -Fq '>研究與行動<' "$footer"; then
    fail 'Compact footer must not duplicate the primary navigation.'
fi

home_html="$(curl --silent --show-error --fail --insecure --location "$BASE_URL/")"
grep -Fq 'site-v2-tokens.css' <<<"$home_html" || fail 'Rendered home page does not load the canonical token stylesheet.'
grep -Fq 'site-v2-page-foundation.css' <<<"$home_html" || fail 'Rendered home page does not load the page-family foundation stylesheet.'
if grep -Eq 'v15-master\.(css|js)' <<<"$home_html"; then
    fail 'Rendered home page still loads retired V15 assets.'
fi
nav_html="$(perl -0ne 'print $1 if m{(<nav class="fw-navbar".*?</nav>)}s' <<<"$home_html")"
grep -Fq '>首頁</a>' <<<"$nav_html" || fail 'Rendered navigation has no visible home item.'
grep -Fq '>聽見田裡的聲音</a>' <<<"$nav_html" || fail 'Rendered media hub label is stale.'
if grep -Fq '了解倡議' <<<"$nav_html"; then
    fail 'Rendered navigation still contains the advocacy CTA.'
fi

published_pages="$(wp post list --post_type=page --post_status=publish --format=count)"
[ "$published_pages" = '18' ] || fail "Published Page baseline changed; expected 18, got $published_pages."

printf 'site v2 global foundation checks passed\n'
