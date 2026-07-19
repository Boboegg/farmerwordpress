#!/usr/bin/env bash
set -euo pipefail

ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
BASE_URL="${BASE_URL:-https://fwdignity-local.local}"
THEME_DIR="$ROOT_DIR/themes/fwdignity"

PUBLICATIONS="$THEME_DIR/page-research-publications.php"
RESOURCES="$THEME_DIR/page-related-resources.php"
ADVOCACY="$THEME_DIR/page-dignity-farming-initiative.php"
ABOUT="$THEME_DIR/page-about.php"

CSS_FILES=(
    "$THEME_DIR/assets/css/page-research-sub.css"
    "$THEME_DIR/assets/css/page-about.css"
    "$THEME_DIR/assets/css/page-about-sub.css"
    "$THEME_DIR/assets/css/page-research-about-refresh.css"
)

TEMPLATES=( "$PUBLICATIONS" "$RESOURCES" "$ADVOCACY" "$ABOUT" )

fail() {
    printf '%s\n' "$1" >&2
    exit 1
}

contains() {
    local needle="$1"
    local file="$2"
    local message="$3"

    grep -Fq "$needle" "$file" || fail "$message"
}

count_is() {
    local needle="$1"
    local file="$2"
    local expected="$3"
    local message="$4"
    local actual

    actual="$(grep -Fo "$needle" "$file" | wc -l | tr -d ' ')"
    [ "$actual" = "$expected" ] || fail "$message (expected $expected, got $actual)"
}

for template in "${TEMPLATES[@]}"; do
    ! grep -Eq 'class="[^"]*\breveal\b|<span class="accent">|data-hook-title|data-magnetic|#[0-9A-Fa-f]{3,8}|rgba?\(|hsla?\(' "$template" || fail "legacy decoration remains in $(basename "$template")"
done

! grep -Eq 'adv-hero-noise|adv-hero-deco|adv-deco-[123]|style="--(pc|pcl|cc)' "$ADVOCACY" || fail 'advocacy template still renders V15 decorative layers or inline color tokens'
! grep -Fq 'ab-field-stats' "$ABOUT" || fail 'about page still promotes source-pending figures as a statistics strip'

contains '地方知識到地方敘事：農業工作者經驗的再現路徑' "$PUBLICATIONS" 'published research content changed unexpectedly'
contains '臺灣農業工作者熱傷害研究' "$PUBLICATIONS" 'in-progress research topic changed unexpectedly'
count_is 'class="rs-pub"' "$PUBLICATIONS" '4' 'research publication template contract changed'
contains 'fwdignity_research_material_ids' "$PUBLICATIONS" 'research materials must come from the configured attachment IDs'
count_is 'class="rs-topic"' "$PUBLICATIONS" '2' 'in-progress research topic count changed'

contains '農業部農民保險局' "$RESOURCES" 'insurance resource content changed unexpectedly'
contains '安心專線 1925' "$RESOURCES" 'support resource content changed unexpectedly'
count_is 'class="rs-pub"' "$RESOURCES" '11' 'related resource count changed'

contains '農民雖然沒有一個實體的慣老闆' "$ADVOCACY" 'advocacy quotation changed unexpectedly'
contains 'EP195:尊嚴農業' "$ADVOCACY" 'advocacy media content changed unexpectedly'
count_is 'class="adv-problem"' "$ADVOCACY" '4' 'advocacy problem count changed'
count_is 'class="adv-pillar"' "$ADVOCACY" '4' 'advocacy pillar count changed'
count_is 'class="adv-tl-item"' "$ADVOCACY" '4' 'advocacy timeline count changed'
count_is 'class="adv-fg"' "$ADVOCACY" '2' 'advocacy field-dialogue count changed'
count_is 'class="adv-media"' "$ADVOCACY" '3' 'advocacy media count changed'

contains '農民是工作者，不是補助對象。' "$ABOUT" 'about page core statement changed unexpectedly'
contains '以上在地數據來源待確認' "$ABOUT" 'about page source caveat changed unexpectedly'
contains '<h2>研究團隊</h2>' "$ABOUT" 'about page team section must use the approved 研究團隊 label'
! grep -Fq '跨領域共同研究' "$ABOUT" || fail 'retired team heading remains in the about template'
count_is 'class="ab-four-item"' "$ABOUT" '4' 'about research dimension count changed'
count_is 'class="ab-action"' "$ABOUT" '4' 'about action count changed'
count_is 'class="ab-team-card"' "$ABOUT" '6' 'about team preview count changed'

for css in "${CSS_FILES[@]}"; do
    ! grep -Eiq '#[0-9a-f]{3,8}|rgba?\(|hsla?\(' "$css" || fail "non-canonical color literal remains in $(basename "$css")"
    ! grep -Fq 'gradient(' "$css" || fail "legacy gradient remains in $(basename "$css")"
    ! grep -Eq 'opacity:[[:space:]]*0([;[:space:]]|$)' "$css" || fail "hidden-by-default content remains in $(basename "$css")"
    contains 'var(--fw-' "$css" "canonical variables are missing from $(basename "$css")"
done

contains 'color: var(--fw-color-ink) !important' "${CSS_FILES[3]}" 'headings must resolve to one canonical ink color'
contains 'background: var(--fw-color-page) !important' "${CSS_FILES[3]}" 'page background must use the Clear Field page color'
contains 'background: var(--fw-color-surface) !important' "${CSS_FILES[3]}" 'content surfaces must use the Clear Field surface color'
contains '.adv-timeline' "${CSS_FILES[3]}" 'advocacy timeline needs the compact final-layout override'

PATHS=(
    'research-results/research-publications'
    'research-results/related-resources'
)
EXPECTED_H1=( '研究出版' '相關資源' )

for index in "${!PATHS[@]}"; do
    path="${PATHS[$index]}"
    html="$(curl --silent --show-error --fail --insecure --location --max-time 20 "$BASE_URL/$path/")"
    grep -Fq 'page-research-about-refresh.css' <<<"$html" || fail "refresh stylesheet is missing: $path"
    grep -Fq ">${EXPECTED_H1[$index]}</h1>" <<<"$html" || fail "first-screen H1 is unclear: $path"
done

[ "$(curl --silent --show-error --insecure --location --output /dev/null --write-out '%{http_code}' "$BASE_URL/dignity-farming-initiative/")" = '404' ] \
    || fail 'hidden advocacy Page must return 404'

publications_html="$(curl --silent --show-error --fail --insecure --location --max-time 20 "$BASE_URL/research-results/research-publications/")"
count_is 'class="rs-pub"' <(printf '%s' "$publications_html") '7' 'rendered research publication page must contain four PDFs and three verified publication records'

about_html="$(curl --silent --show-error --fail --insecure --location --max-time 20 "$BASE_URL/about/")"
grep -Fq 'page-research-about-refresh.css' <<<"$about_html" || fail 'refresh stylesheet is missing: about'
grep -Fq '尊嚴農業：' <<<"$about_html" || fail 'about first-screen purpose is missing'
! grep -Fq 'class="ab-field-stats"' <<<"$about_html" || fail 'rendered about page still includes the source-pending statistics strip'
! grep -Fq '跨領域共同研究' <<<"$about_html" || fail 'rendered about page still uses the retired team heading'

printf 'research/about v3 LocalWP layout test passed\n'
