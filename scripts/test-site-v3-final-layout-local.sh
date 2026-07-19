#!/usr/bin/env bash
set -u

repo_dir="$(cd "$(dirname "$0")/.." && pwd)"
theme_dir="$repo_dir/themes/fwdignity"
css="$theme_dir/assets/css/site-v3-layout-final.css"
functions="$theme_dir/functions.php"
PHP_BIN="${PHP_BIN:-$HOME/Library/Application Support/Local/lightning-services/php-8.3.30+1/bin/darwin-arm64/bin/php}"
failures=0

pass() {
  printf 'PASS: %s\n' "$1"
}

fail() {
  printf 'FAIL: %s\n' "$1" >&2
  failures=$((failures + 1))
}

require_fixed() {
  local file="$1"
  local needle="$2"
  local label="$3"
  if grep -Fq -- "$needle" "$file"; then
    pass "$label"
  else
    fail "$label"
  fi
}

if [[ -f "$css" ]]; then
  pass 'final layout stylesheet exists'
else
  fail 'final layout stylesheet exists'
  exit 1
fi

require_fixed "$functions" "'fwdignity-site-v3-final-layout'" 'final layout stylesheet is registered'
require_fixed "$functions" "'/assets/css/site-v3-layout-final.css'" 'final layout stylesheet path is registered'
require_fixed "$functions" "array( 'fwdignity-v2-page-foundation' )" 'final layout loads after the canonical page foundation'

require_fixed "$css" '/* 01 首頁：B 編輯頭版＋A 圓潤外框 */' 'home final layout is declared'
require_fixed "$css" 'grid-template-columns: minmax(0, 1.8fr) minmax(320px, .8fr)' 'home uses editorial headline proportions'
require_fixed "$css" 'border-radius: var(--fw-radius-card)' 'home uses canonical rounded outer surface'

require_fixed "$css" '/* 02 全部內容：左側篩選＋一致卡片網格 */' 'all-content layout is declared'
require_fixed "$css" 'grid-template-columns: 264px minmax(0, 1fr)' 'all-content page keeps the left filter column'
require_fixed "$css" 'grid-template-columns: repeat(auto-fill, minmax(280px, 1fr))' 'all-content results use one consistent card grid'
require_fixed "$css" 'grid-column: auto !important' 'all-content cards do not promote the first item into a spanning lead card'

require_fixed "$css" '/* 03 田間知識與資源：單層六卡 */' 'guide single-layer layout is declared'
require_fixed "$css" '.fg-guide__quick-nav' 'guide duplicate quick navigation is targeted'
require_fixed "$css" 'grid-template-columns: repeat(3, minmax(0, 1fr))' 'guide desktop uses a three-column six-card grid'

require_fixed "$css" '/* 04 田野入口：A 田野雜誌 */' 'field magazine layout is declared'
require_fixed "$css" '.field-family-pathways' 'field duplicate pathways are targeted'
require_fixed "$css" 'grid-template-columns: minmax(0, 1.55fr) minmax(340px, .85fr)' 'field lead story uses magazine proportions'

require_fixed "$css" '/* 05 五個田野主題：A 問題專刊 */' 'topic feature layout is declared'
require_fixed "$css" 'grid-template-columns: minmax(0, 1.45fr) minmax(340px, .75fr)' 'topic feature gives the readable copy column priority'

require_fixed "$css" '/* 06 聽見田裡的聲音：A 主打單集＋節目架 */' 'media feature layout is declared'
require_fixed "$css" '.mh-archive-list' 'media archive shelf is targeted'

require_fixed "$css" '/* 07 研究、資源與倡議：A 研究到行動路徑 */' 'research action-path layout is declared'
require_fixed "$css" '.ra-action-grid' 'research action grid is targeted'

require_fixed "$css" '/* 08 認識尊嚴農業與研究團隊：A 計畫敘事 */' 'about narrative layout is declared'
require_fixed "$css" '.ab-field' 'about field narrative is targeted'

require_fixed "$css" '/* 09 文章單篇：C 雜誌開場 */' 'single magazine opening is declared'
require_fixed "$css" '.article-entry:has(.article-featured-image)' 'single layout safely requires a real featured image'

require_fixed "$css" '@media (max-width: 800px)' 'mobile layout contract exists'

color_scan="$(sed 's/#ADB850//g' "$css")"
if grep -Eq '#[0-9A-Fa-f]{3,8}|rgb\(|hsl\(' <<<"$color_scan"; then
  fail 'final layout stylesheet contains no unapproved literal colors'
else
  pass 'final layout stylesheet only keeps the approved Podcast media edge color'
fi

footer_blocks="$(awk 'BEGIN { RS = "}" } /\.fw-footer|footer\.fw-|site-footer/ { print $0 "}" }' "$css")"
if grep -Eq '(^|[[:space:]])(background|display|grid-template-columns|padding|margin|width|height|color|border):' <<<"$footer_blocks"; then
  fail 'final layout stylesheet must not change the approved footer geometry or colors'
else
  pass 'final layout stylesheet limits footer handling to canonical typography'
fi

navbar_blocks="$(awk 'BEGIN { RS = "}" } /\.fw-navbar/ { print $0 "}" }' "$css")"
if grep -Fq 'border-top' <<<"$navbar_blocks"; then
  fail 'final layout stylesheet leaves the existing navbar top rule untouched'
else
  pass 'final layout stylesheet leaves the existing navbar top rule untouched'
fi

if "$PHP_BIN" -r '$s=file_get_contents($argv[1]); $s=preg_replace("~/\\*.*?\\*/~s", "", $s); exit(substr_count($s,"{")===substr_count($s,"}") ? 0 : 1);' "$css"; then
  pass 'final layout CSS braces are balanced'
else
  fail 'final layout CSS braces are balanced'
fi

if "$PHP_BIN" -l "$functions" >/dev/null; then
  pass 'functions.php passes PHP lint'
else
  fail 'functions.php passes PHP lint'
fi

if (( failures > 0 )); then
  printf '\n%d contract check(s) failed.\n' "$failures" >&2
  exit 1
fi

printf '\nAll final-layout contract checks passed.\n'
