#!/usr/bin/env bash
set -euo pipefail

repo_dir="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
tokens="$repo_dir/themes/fwdignity/assets/css/site-v2-tokens.css"
foundation="$repo_dir/themes/fwdignity/assets/css/site-v2-page-foundation.css"

fail() {
    printf '%s\n' "$1" >&2
    exit 1
}

for file in "$tokens" "$foundation"; do
    [ -f "$file" ] || fail "Required Clear Field stylesheet is missing: $file"
done

assert_token() {
    local name="$1"
    local value="$2"
    local declaration="  --${name}: ${value};"
    local count

    count="$(grep -Fic -- "$declaration" "$tokens" || true)"
    [ "$count" = '1' ] || fail "Expected one canonical token declaration: $declaration (found $count)."
}

while read -r name value; do
    assert_token "$name" "$value"
done <<'TOKENS'
fw-color-page #FDFBF7
fw-color-paper #F5F5F2
fw-color-surface #FFFFFF
fw-color-surface-alt #F0F0EC
fw-color-primary #435832
fw-color-primary-deep #314225
fw-color-ink #283121
fw-color-muted #566155
fw-color-reading #1A1A1A
fw-color-reading-muted #4A4A4A
fw-color-link #435832
fw-color-link-hover #314225
fw-color-accent #A57E26
fw-color-accent-text #7B5D12
fw-color-line #E0E0E0
fw-color-focus #283121
fw-color-focus-separator #F7F6F1
fw-color-danger #A24A3D
fw-color-media-background #F5F5F2
fw-color-media-card #FFFFFF
fw-color-footer-background #25331E
fw-home-media-band #435832
fw-home-media-band-card #34452D
fw-color-on-dark #F7F6F1
fw-color-on-dark-muted #DCE4D8
fw-topic-field #376E7C
fw-topic-research #376E7C
TOKENS

legacy_colors=(
    '#FBF3E6' '#EEE9D5' '#FFFDF8' '#F3EFE1'
    '#2F6675' '#224D59' '#B8913C' '#765A20' '#D6CEB7'
    '#674A70' '#7C3AED' '#EDE9FE' '#4C1D95'
)

for color in "${legacy_colors[@]}"; do
    if grep -Fiq -- "$color" "$tokens" "$foundation"; then
        fail "Retired warm-yellow or V15 purple color remains in active color governance CSS: $color"
    fi
done

deep_count="$(grep -Fic -- '#25331E' "$tokens")"
[ "$deep_count" = '1' ] || fail "Footer deep color must have exactly one token declaration; found $deep_count."

grep -Fq 'color: var(--fw-color-ink) !important;' "$foundation" \
    || fail 'Monochrome heading ink override is missing.'
grep -Fq 'color: inherit !important;' "$foundation" \
    || fail 'Heading descendants do not inherit one monochrome heading color.'
grep -Fq 'color: var(--fw-color-link) !important;' "$foundation" \
    || fail 'Canonical link color override is missing.'
grep -Fq 'text-decoration-line: underline !important;' "$foundation" \
    || fail 'Canonical text links are not underlined.'
grep -Fq 'main a:where(' "$foundation" \
    || fail 'Canonical text-link exclusions must not outrank monochrome heading rules.'

# 2026-07-17 Phase D 批①：hero on-dark 規則逐字下沉至 home.css 治理下沉區，契約改指新位置。
if ! awk '
    BEGIN { RS = "}"; found = 0 }
    /body\[data-page="home"\][[:space:]]+\.fw-home-v04-hero[[:space:]]+:is\(h1, h2, h3, h4\)/ &&
    /color:[[:space:]]*var\(--fw-color-on-dark\)/ { found = 1 }
    END { exit found ? 0 : 1 }
' "$repo_dir/themes/fwdignity/assets/css/home.css"; then
    fail 'The photographic home hero must use the on-dark heading color for legibility.'
fi

# 2026-07-17 Phase D 批①：home 家族治理下沉至 home.css，負向白名單同步涵蓋兩檔。
for _ondark_file in "$foundation" "$repo_dir/themes/fwdignity/assets/css/home.css"; do
if awk '
    BEGIN { RS = "}"; found = 0 }
    /h[1-4]/ && /color:[[:space:]]*var\(--fw-color-on-dark\)/ &&
    !/body\[data-page="home"\][[:space:]]+\.fw-home-v04-hero/ &&
    !/body\[data-page="home"\][[:space:]]+\.fw-home-v04-audio/ { found = 1 }
    END { exit found ? 0 : 1 }
' "$_ondark_file"; then
    fail 'An on-dark heading appears outside the photographic hero or the approved home media band.'
fi
done

if ! awk '
    BEGIN { RS = "}"; found = 0 }
    /body\[data-page="home"\][[:space:]]+\.fw-home-v04-audio/ &&
    /background:[[:space:]]*var\(--fw-home-media-band\)[[:space:]]*!important/ { found = 1 }
    END { exit found ? 0 : 1 }
' "$repo_dir/themes/fwdignity/assets/css/home.css"; then
    fail 'The approved dark home media-band treatment is missing.'
fi

if ! awk '
    BEGIN { RS = "}"; found = 0 }
    /body\[data-page="home"\][[:space:]]+\.fw-home-v06-media-card/ &&
    /background:[[:space:]]*var\(--fw-home-media-band-card\)[[:space:]]*!important/ { found = 1 }
    END { exit found ? 0 : 1 }
' "$repo_dir/themes/fwdignity/assets/css/home.css"; then
    fail 'The approved dark home media-card treatment is missing.'
fi

grep -Fq 'background: var(--fw-color-page) !important;' "$foundation" \
    || fail 'Neutral large-surface override is missing.'

grep -Fq 'border-color: var(--fw-topic-current) !important;' "$foundation" \
    || fail 'Topic color is not constrained to a small-area border treatment.'
grep -Fq 'background: transparent !important;' "$foundation" \
    || fail 'Topic markers are not protected from solid topic-color fills.'

if grep -Eq 'color-mix\([^;]*var\(--fw-topic-' "$foundation"; then
    fail 'Topic color is still mixed into a large-surface alias; topic colors are small-area only.'
fi

# 2026-07-17 Phase D 批②：--fe-paper 別名隨田野家族治理下沉至 page-field-events.css（值逐字不變）。
grep -Fq -- '--fe-paper: var(--fw-color-paper);' "$repo_dir/themes/fwdignity/assets/css/page-field-events.css" \
    || fail 'Legacy page-family color alias is not governed: --fe-paper (page-field-events.css)'

# 2026-07-17 Phase D 批③：休眠家族 adapter 隨治理搬遷移入各家族檔（值逐字不變），
# 斷言逐檔改指新位置；--rr-* 全 theme 零消費經實證後整塊刪除，改為「不得存在」反向斷言（契約更強）。
css_dir="$repo_dir/themes/fwdignity/assets/css"
check_alias() {
    grep -Fq -- "$2" "$css_dir/$1" \
        || fail "Legacy page-family color alias is not governed: $2 ($1)"
}
check_alias page-health-sub.css '--hps-main: var(--fw-topic-health);'
check_alias page-health-sub.css '--hps-bg: var(--fw-color-paper);'
check_alias page-safety.css '--os-main: var(--fw-topic-safety);'
check_alias page-safety.css '--os-bg: var(--fw-color-paper);'
check_alias page-safety-sub.css '--os-main: var(--fw-topic-safety);'
check_alias page-economic-insurance.css '--ei-main: var(--fw-topic-policy);'
check_alias page-economic-insurance.css '--ei-bg: var(--fw-color-paper);'
check_alias page-research-sub.css '--rs-accent: var(--fw-topic-research);'
check_alias page-research-sub.css '--rs-accent-bg: var(--fw-color-surface-alt);'
check_alias page-advocacy.css '--adv-bg: var(--fw-color-paper);'
check_alias page-advocacy.css '--pcl: var(--fw-color-surface-alt) !important;'
check_alias page-advocacy.css '--ccl: var(--fw-color-surface-alt) !important;'
check_alias page-research-result.css '--ccl: var(--fw-color-surface-alt) !important;'
if grep -rq -- 'var(--rr-' "$css_dir" --include='*.css' --exclude-dir=retired-20260716 2>/dev/null; then
    fail 'Deleted --rr-* alias family has regained consumers; reinstate governance before use.'
fi

grep -Fq '[data-page="infographic"], [data-page="download-zone"]' "$foundation" \
    || fail 'Research topic governance does not cover infographic and download-zone.'
# 批③：.adv-cta-card 光面角色隨 advocacy 搬遷；兩處分別驗證（涵蓋不減）。
grep -Fq '.ab-final-cta' "$foundation" \
    || fail 'About final CTA surface is not constrained to a light role.'
grep -Fq -- '.adv-hero, .adv-cta-card' "$css_dir/page-advocacy.css" \
    || fail 'Advocacy CTA surfaces are not constrained to a light role (page-advocacy.css).'

# 批③：hps-stretch 健康色框覆寫隨健康家族搬遷至 page-health-sub.css（值逐字不變）。
grep -Fq 'border-top-color: var(--fw-topic-health) !important;' "$css_dir/page-health-sub.css" \
    || fail 'Hard-coded legacy health purple border is not overridden.'

printf 'Clear Field static color governance checks passed\n'
