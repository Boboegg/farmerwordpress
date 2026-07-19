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

wp eval '
    $expected_drafts = array( 925, 1762, 2065 );
    foreach ( $expected_drafts as $post_id ) {
        $status = get_post_status( $post_id );
        if ( "draft" !== $status ) {
            fwrite( STDERR, "Expected draft status for {$post_id}; got {$status}.\n" );
            exit( 1 );
        }
    }
    echo "Page status checks passed.\n";
'

team_template="$THEME_DIR/page-researchteam.php"
footer_template="$THEME_DIR/footer.php"

[ -f "$team_template" ] || fail 'Research team template is missing.'
[ -f "$footer_template" ] || fail 'Footer template is missing.'

member_count="$(grep -Fc "array( 'name'=>'" "$team_template")"
[ "$member_count" = '18' ] || fail "Research team template must contain 18 members; got $member_count."

if grep -Fq '卓怡萱' "$team_template"; then
    fail 'Former team member 卓怡萱 must not appear in the current template.'
fi

grep -Fq "array( 'name'=>'陳丹怡', 'role'=>'博士後研究員', 'org'=>'高齡社會勞動與福祉研究中心', 'desc'=>'國立中正大學', 'img'=>'uploads/2025/12/485657-scaled.jpg' )" "$team_template" \
    || fail '陳丹怡 selected representative photo mapping changed.'
grep -Fq "array( 'name'=>'曾志桓', 'role'=>'專案助理', 'org'=>'高齡社會勞動與福祉研究中心', 'desc'=>'國立中正大學', 'img'=>'uploads/2025/12/S__10928178.jpg' )" "$team_template" \
    || fail '曾志桓 selected representative photo mapping changed.'
# 2026-07-16 W2-B（Bobo 拍板）：無照成員用貓咪代表照。
grep -Fq "array( 'name'=>'陳俊榮', 'role'=>'專任助理', 'org'=>'', 'desc'=>'', 'img'=>'uploads/2025/12/485657-portrait2-20260716.jpg' )" "$team_template" \
    || fail 'New full-time assistant 陳俊榮 is missing or has unconfirmed fields.'

grep -Fq 'id="footer-contact"' "$footer_template" || fail 'Footer contact section is missing.'
grep -Fq "fwdignity_get( 'address' )" "$footer_template" || fail 'Footer address source is missing.'
grep -Fq "fwdignity_get( 'phone' )" "$footer_template" || fail 'Footer phone source is missing.'
grep -Fq "fwdignity_get( 'email' )" "$footer_template" || fail 'Footer email source is missing.'

team_html="$(curl --silent --show-error --fail --insecure --location "$BASE_URL/about/researchteam/")"
rendered_member_count="$(grep -o 'class="as-team-card[^\"]*"' <<<"$team_html" | wc -l | tr -d ' ')"
[ "$rendered_member_count" = '18' ] || fail "Rendered team page must contain 18 member cards; got $rendered_member_count."
# 2026-07-16 W2-A：data-hook-title 屬 v15 逐字進場殘留，已依清朗田野規範移除。
grep -Fq '<h1>研究團隊</h1>' <<<"$team_html" || fail 'Rendered team title must be 研究團隊.'
if grep -Fq '跨領域共同研究' <<<"$team_html" || grep -Fq '共 18 位成員共同推動農業工作者職業健康的社會安全網建構' <<<"$team_html"; then
    fail 'Removed research team hero copy is still rendered.'
fi
grep -Fq '陳俊榮' <<<"$team_html" || fail '陳俊榮 is not rendered on the team page.'
grep -Fq '485657-scaled.jpg' <<<"$team_html" || fail '陳丹怡 selected representative photo is not rendered.'
grep -Fq 'S__10928178.jpg' <<<"$team_html" || fail '曾志桓 selected representative photo is not rendered.'

printf 'site v2 foundation LocalWP checks passed\n'
