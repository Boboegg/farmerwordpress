#!/usr/bin/env bash
set -u

repo_dir="$(cd "$(dirname "$0")/.." && pwd)"
theme_dir="$repo_dir/themes/fwdignity"
base_url="${BASE_URL:-https://fwdignity-local.local}"
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

reject_fixed() {
  local file="$1"
  local needle="$2"
  local label="$3"
  if grep -Fq -- "$needle" "$file"; then
    fail "$label"
  else
    pass "$label"
  fi
}

assert_block_contains() {
  local file="$1"
  local selector="$2"
  local declaration="$3"
  local label="$4"
  if awk -v selector="$selector" -v declaration="$declaration" '
      BEGIN { RS = "}"; found = 0 }
      index($0, selector) && index($0, declaration) { found = 1 }
      END { exit found ? 0 : 1 }
    ' "$file"; then
    pass "$label"
  else
    fail "$label"
  fi
}

header="$theme_dir/header.php"
layout="$theme_dir/assets/css/layout.css"
thenews_template="$theme_dir/page-thenews.php"
thenews_css="$theme_dir/assets/css/page-thenews.css"
thenews_js="$theme_dir/assets/js/page-thenews.js"
field_css="$theme_dir/assets/css/page-field-learning.css"
people_css="$theme_dir/assets/css/page-people-who-farm.css"
research_css="$theme_dir/assets/css/page-research-about-refresh.css"
final_css="$theme_dir/assets/css/site-v3-layout-final.css"
functions="$theme_dir/functions.php"

require_fixed "$header" 'aria-label="開啟首頁子選單"' '首頁提供可操作的子選單'
require_fixed "$header" '>全部內容</a>' '全部內容收進首頁子選單'
reject_fixed "$header" '>內容資料庫</a>' '主導覽不再使用內容資料庫名稱'

assert_block_contains "$layout" '.fw-dropdown {' 'top: 100%;' '桌機子選單緊貼觸發項目，沒有 hover 斷層'
reject_fixed "$layout" 'top: calc(100% + 6px);' '舊的六像素 hover 空隙已移除'

require_fixed "$thenews_template" "\$title_parts['title'] = '全部內容';" '內容索引的文件標題為全部內容'
require_fixed "$thenews_template" '<h1>全部內容</h1>' '內容索引的頁面標題為全部內容'
require_fixed "$thenews_template" 'data-auto-submit' '篩選表單宣告即時送出行為'
require_fixed "$thenews_js" 'requestSubmit()' '篩選條件變更後由瀏覽器送出表單'
require_fixed "$thenews_css" 'max-width: none;' '內容索引副標可使用完整內容寬度'

assert_block_contains "$field_css" '.field-index-band {' 'background: transparent;' '田野索引不再使用大面積鐵灰底'
assert_block_contains "$final_css" 'body[data-page="people-who-farm"] .pf-question-grid {' 'grid-template-columns: repeat(2, minmax(0, 1fr))' '做農的人採穩定雙欄卡片版型'
assert_block_contains "$final_css" 'body[data-page="people-who-farm"] .pf-question-entry:not(:first-child)' 'grid-template-columns: var(--fw-thumb-list-width, 220px) minmax(0, 1fr)' '做農的人一般卡片使用既定清單縮圖角色'
assert_block_contains "$research_css" '.ra-intro {' 'max-width: none;' '研究頁首可使用完整內容寬度'
require_fixed "$final_css" '/* 2026-07-14 全站桌機資訊層級與密度收斂 */' '最終樣式層有單一桌機收斂規則'
# 2026-07-17 W2-C：cache-busting 改由 fwdignity_bust_asset_version() 於輸出端統一以 filemtime 覆寫。
require_fixed "$functions" 'function fwdignity_bust_asset_version' '主題資產以檔案時間戳記更新快取（filter 定義）'
require_fixed "$functions" "add_filter( 'script_loader_src', 'fwdignity_bust_asset_version' )" '腳本快取戳記 filter 已掛載'

live_html="$(curl --silent --show-error --fail --insecure --location "$base_url/thenews/" 2>/dev/null || true)"
if grep -Fq '<h1>全部內容</h1>' <<<"$live_html"; then
  pass 'LocalWP 已輸出全部內容頁面標題'
else
  fail 'LocalWP 已輸出全部內容頁面標題'
fi

if (( failures > 0 )); then
  printf '\n%d contract check(s) failed.\n' "$failures" >&2
  exit 1
fi

printf '\nContent index, navigation, and density checks passed.\n'
