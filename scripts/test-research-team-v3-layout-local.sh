#!/usr/bin/env bash
set -euo pipefail

ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
PHP="${PHP:-$HOME/Library/Application Support/Local/lightning-services/php-8.3.30+1/bin/darwin-arm64/bin/php}"
WP_CLI="${WP_CLI:-/Applications/Local.app/Contents/Resources/extraResources/bin/wp-cli/wp-cli.phar}"
WP_PATH="${WP_PATH:-$HOME/Local Sites/fwdignity-local/app/public}"
BASE_URL="${BASE_URL:-https://fwdignity-local.local}"
MYSQL_UNIX_PORT="${MYSQL_UNIX_PORT:-$HOME/Library/Application Support/Local/run/FdpHP5Axi/mysql/mysqld.sock}"
RESEARCH_TEMPLATE="$ROOT_DIR/themes/fwdignity/page-research-results.php"
TEAM_TEMPLATE="$ROOT_DIR/themes/fwdignity/page-researchteam.php"
REFRESH_CSS="$ROOT_DIR/themes/fwdignity/assets/css/page-research-about-refresh.css"

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

contains() {
    local needle="$1"
    local file="$2"
    local message="$3"

    grep -Fq "$needle" "$file" || fail "$message"
}

contains '<?php the_title(); ?>' "$RESEARCH_TEMPLATE" 'research title must come from WordPress the_title()'
[ "$(grep -Foc '<?php the_title(); ?>' "$RESEARCH_TEMPLATE")" = '2' ] || fail 'research breadcrumb and H1 must both use the_title()'
! grep -Fq '從研究到行動' "$RESEARCH_TEMPLATE" || fail 'research template still hardcodes the retired title'
contains '尊嚴農業的研究從農業工作者面對的真實問題開始。' "$RESEARCH_TEMPLATE" 'research lead copy changed unexpectedly'
contains '行動不是研究結束後才附加的宣傳' "$RESEARCH_TEMPLATE" 'research action copy changed unexpectedly'

# 2026-07-16 W2-B（Bobo 拍板）：無照成員一律用貓咪代表照；陳俊榮補第三張（同貓近照裁切）。
contains "'name'=>'陳俊榮', 'role'=>'專任助理', 'org'=>'', 'desc'=>'', 'img'=>'uploads/2025/12/485657-portrait2-20260716.jpg'" "$TEAM_TEMPLATE" '陳俊榮 cat placeholder photo is missing'
contains "'img'=>'uploads/2025/12/485657-scaled.jpg'" "$TEAM_TEMPLATE" 'the first intentional cat photo was removed'
contains "'img'=>'uploads/2025/12/S__10928178.jpg'" "$TEAM_TEMPLATE" 'the second intentional cat photo was removed'
contains 'as-team-card--text-only' "$TEAM_TEMPLATE" 'members without photos need the compact text-card variant'
! grep -Eq '\$placeholder|mb_substr|as-team-placeholder' "$TEAM_TEMPLATE" || fail 'team template still creates a fake photo placeholder'
! grep -Eiq '#[0-9a-f]{3,8}|rgba?\(|hsla?\(' "$TEAM_TEMPLATE" || fail 'team template CSS contains a non-canonical color literal'

! grep -Eiq '#[0-9a-f]{3,8}|rgba?\(|hsla?\(' "$REFRESH_CSS" || fail 'refresh CSS contains a non-canonical color literal'
! grep -Fq -- '--refresh-' "$REFRESH_CSS" || fail 'refresh CSS still uses local color aliases instead of canonical vars'
contains 'background: var(--fw-color-page)' "$REFRESH_CSS" 'research main background must use the canonical page color'
contains 'background: var(--fw-color-surface)' "$REFRESH_CSS" 'cards must use the canonical surface color'
contains 'aspect-ratio: 1 / 1' "$REFRESH_CSS" 'team photo cards must retain a stable media ratio'
contains 'align-items: start' "$REFRESH_CSS" 'team rows must not stretch text-only cards to photo-card height'
paper_uses="$(grep -Fo 'var(--fw-color-paper)' "$REFRESH_CSS" | wc -l | tr -d ' ')"
[ "$paper_uses" -le 4 ] || fail 'paper color is used too frequently across the research layout'

wp eval '
    $page = get_page_by_path( "research-results", OBJECT, "page" );
    if ( ! $page || "publish" !== $page->post_status || "研究與行動" !== $page->post_title ) {
        fwrite( STDERR, "research-results must be a published Page titled 研究與行動\n" );
        exit( 1 );
    }
    echo "research Page title check passed\n";
'

research_html="$(curl --silent --show-error --fail --insecure --location --max-time 20 "$BASE_URL/research-results/")"
team_html="$(curl --silent --show-error --fail --insecure --location --max-time 20 "$BASE_URL/about/researchteam/")"

grep -Fq '<span aria-current="page">研究與行動</span>' <<<"$research_html" || fail 'rendered research breadcrumb does not use the WordPress title'
grep -Fq '<h1>研究與行動</h1>' <<<"$research_html" || fail 'rendered research H1 does not use the WordPress title'

text_card_count="$(grep -Fo 'as-team-card--text-only' <<<"$team_html" | wc -l | tr -d ' ')"
photo_count="$(grep -Fo 'class="as-team-photo"' <<<"$team_html" | wc -l | tr -d ' ')"
# 2026-07-16 W2-B（Bobo 拍板）：陳俊榮補貓咪代表照後，純文字卡 3→2、照片卡 15→16。
[ "$text_card_count" = '2' ] || fail 'team page must render exactly two compact text-only cards'
[ "$photo_count" = '16' ] || fail 'team page must render photo media only for the sixteen members with images'
grep -Fq '485657-scaled.jpg' <<<"$team_html" || fail 'the first intentional cat photo is not rendered'
grep -Fq 'S__10928178.jpg' <<<"$team_html" || fail 'the second intentional cat photo is not rendered'

printf 'research/team v3 LocalWP layout test passed\n'
