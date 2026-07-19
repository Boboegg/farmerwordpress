#!/usr/bin/env bash
set -u

ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
THEME="$ROOT/themes/fwdignity"
TOKENS="$THEME/assets/css/site-v2-tokens.css"
FOUNDATION="$THEME/assets/css/site-v2-page-foundation.css"
LAYOUT="$THEME/assets/css/layout.css"
PARTS="$THEME/assets/css/site-parts.css"
READER="$THEME/assets/js/reader-size-control.js"
FUNCTIONS="$THEME/functions.php"

failures=0
passes=0

pass() {
  printf 'PASS  %s\n' "$1"
  passes=$((passes + 1))
}

fail() {
  printf 'FAIL  %s\n' "$1"
  failures=$((failures + 1))
}

require_file() {
  local label="$1" file="$2"
  if [[ -f "$file" ]]; then pass "$label"; else fail "${label}（缺少 ${file}）"; fi
}

require_literal() {
  local label="$1" file="$2" literal="$3"
  if [[ -f "$file" ]] && rg -q --fixed-strings -- "$literal" "$file"; then
    pass "$label"
  else
    fail "$label"
  fi
}

require_regex() {
  local label="$1" file="$2" pattern="$3"
  if [[ -f "$file" ]] && rg -q -U --pcre2 -- "$pattern" "$file"; then
    pass "$label"
  else
    fail "$label"
  fi
}

forbid_regex() {
  local label="$1" file="$2" pattern="$3"
  if [[ -f "$file" ]] && ! rg -q -U --pcre2 -- "$pattern" "$file"; then
    pass "$label"
  else
    fail "$label"
  fi
}

printf '清朗田野 3.3.0 設計契約\n'

require_file 'canonical token 檔存在' "$TOKENS"
require_file '公共頁裁決層存在' "$FOUNDATION"
require_file '讀者字級控制腳本存在' "$READER"

# Color roles.
require_literal '頁面底色 #FDFBF7' "$TOKENS" '--fw-color-page: #FDFBF7;'
require_literal '次區塊紙色 #F5F5F2' "$TOKENS" '--fw-color-paper: #F5F5F2;'
require_literal '內容面 #FFFFFF' "$TOKENS" '--fw-color-surface: #FFFFFF;'
require_literal '次內容面 #F0F0EC' "$TOKENS" '--fw-color-surface-alt: #F0F0EC;'
require_literal '長文正文 #1A1A1A' "$TOKENS" '--fw-color-reading: #1A1A1A;'
require_literal '長文次要文字 #4A4A4A' "$TOKENS" '--fw-color-reading-muted: #4A4A4A;'
require_literal '一般連結沿用品牌綠' "$TOKENS" '--fw-color-link: #435832;'
require_literal '連結 hover 沿用品牌深綠' "$TOKENS" '--fw-color-link-hover: #314225;'
require_literal '中性邊線 #E0E0E0' "$TOKENS" '--fw-color-line: #E0E0E0;'
require_literal '焦點外圈 #283121' "$TOKENS" '--fw-color-focus: #283121;'
require_literal '焦點分隔圈 #F7F6F1' "$TOKENS" '--fw-color-focus-separator: #F7F6F1;'
require_literal '頁尾固定 #25331E' "$TOKENS" '--fw-color-footer-background: #25331E;'
require_literal '首頁影音帶保留 #435832' "$TOKENS" '--fw-home-media-band: #435832;'
require_literal '首頁影音卡保留 #34452D' "$TOKENS" '--fw-home-media-band-card: #34452D;'

# Standard typography and geometry.
require_literal 'H1 48px' "$TOKENS" '--fw-font-size-h1: 48px;'
require_literal 'H1 行高 1.32' "$TOKENS" '--fw-line-height-h1: 1.32;'
require_literal 'H2 34px' "$TOKENS" '--fw-font-size-h2: 34px;'
require_literal 'H3 24px' "$TOKENS" '--fw-font-size-h3: 24px;'
require_literal 'H4 20px' "$TOKENS" '--fw-font-size-h4: 20px;'
require_literal '卡片標題 22px' "$TOKENS" '--fw-font-size-card-title: 22px;'
require_literal '一般正文 18px' "$TOKENS" '--fw-font-size-body: 18px;'
require_literal '長文正文 20px' "$TOKENS" '--fw-font-size-long-body: 20px;'
require_literal '摘要 17px' "$TOKENS" '--fw-font-size-summary: 17px;'
require_literal '控制 16px' "$TOKENS" '--fw-font-size-control: 16px;'
require_literal 'metadata 15px' "$TOKENS" '--fw-font-size-meta: 15px;'
require_literal '一般正文行高 1.80' "$TOKENS" '--fw-line-height-body: 1.8;'
require_literal '長文行高 1.85' "$TOKENS" '--fw-line-height-long-body: 1.85;'
require_literal '外層 14px 圓角' "$TOKENS" '--fw-radius-card: 14px;'
require_literal '控制 4px 圓角' "$TOKENS" '--fw-radius-control: 4px;'
require_literal '標籤 2px 圓角' "$TOKENS" '--fw-radius-tag: 2px;'

# B role-based depth and motion.
require_literal '工作工具陰影' "$TOKENS" '--fw-shadow-tool: 0 4px 14px rgba(37, 51, 30, .06);'
require_literal '浮層陰影' "$TOKENS" '--fw-shadow-popover: 0 16px 40px rgba(37, 51, 30, .16);'
require_literal '浮動控制陰影' "$TOKENS" '--fw-shadow-floating: 0 6px 18px rgba(37, 51, 30, .18);'
require_literal '共同動態時間 160ms' "$TOKENS" '--fw-motion-duration: 160ms;'
require_literal '共同 ease-out' "$TOKENS" '--fw-ease-out: ease-out;'

# Final cascade rules.
require_regex 'H1 固定 700 且讀者級別不放大' "$FOUNDATION" 'main h1[\s\S]{0,260}font-size:\s*var\(--fw-font-size-h1\)[\s\S]{0,180}font-weight:\s*700'
require_regex '一般正文使用 18px 角色' "$FOUNDATION" 'main\s+:is\(p, li\)[\s\S]{0,180}var\(--fw-font-size-body\)'
# 2026-07-17 Phase D 批①：長文規則逐字下沉至 single-post.css 治理下沉區，契約改指新位置。
require_regex '長文使用黑色與 long-body token' "$THEME/assets/css/single-post.css" '\.article-content[\s\S]{0,320}var\(--fw-color-reading\)[\s\S]{0,220}var\(--fw-font-size-long-body\)'
require_regex '控制使用 control 字級' "$FOUNDATION" 'button, input, select, textarea[\s\S]{0,220}var\(--fw-font-size-control\)'
require_regex '雙層鍵盤 focus' "$FOUNDATION" ':focus-visible[\s\S]{0,260}outline:\s*3px solid var\(--fw-color-focus\)[\s\S]{0,160}var\(--fw-color-focus-separator\)'
require_regex '精準指標才啟用 hover' "$FOUNDATION" '@media\s*\(hover:\s*hover\)\s*and\s*\(pointer:\s*fine\)'
require_regex '照片保持原色' "$FOUNDATION" 'img[\s\S]{0,180}filter:\s*none'
require_regex 'reduced motion 規則存在' "$FOUNDATION" '@media\s*\(prefers-reduced-motion:\s*reduce\)'
forbid_regex '裁決層 transition 不含 transform' "$FOUNDATION" 'transition-property:[^;]*transform'
forbid_regex '裁決層不使用 transition all' "$FOUNDATION" 'transition\s*:\s*all\b'
forbid_regex '共用頁首不使用毛玻璃' "$LAYOUT" '(?:-webkit-)?backdrop-filter\s*:'
forbid_regex '共用頁尾不再用膠囊標籤' "$LAYOUT" 'border-radius:\s*999px'
forbid_regex '共用頁尾 hover 不位移' "$LAYOUT" '\.fw-footer[^\{]*:hover[\s\S]{0,180}transform\s*:'
forbid_regex '共用側欄 hover 不位移' "$PARTS" ':hover[^{]*\{[^}]*transform\s*:'
# 2026-07-17 Phase D 批①：首頁影音帶規則逐字下沉至 home.css 治理下沉區，契約改指新位置。
require_regex '首頁影音帶有 28px 紙白緩衝' "$THEME/assets/css/home.css" 'fw-home-v04-audio[\s\S]{0,320}margin-block:\s*28px'
require_regex '首頁影音帶內距 46px' "$THEME/assets/css/home.css" 'fw-home-v04-audio[\s\S]{0,420}padding-block:\s*46px'

# Reader control behavior and enqueue.
require_literal '偏好儲存 key 固定' "$READER" "fwdignity-reader-size"
require_literal '三段包含 standard' "$READER" "standard"
require_literal '三段包含 large' "$READER" "large"
require_literal '三段包含 xlarge' "$READER" "xlarge"
require_literal '入口具 aria-expanded' "$READER" "aria-expanded"
require_literal '選項具 aria-pressed' "$READER" "aria-pressed"
require_literal '支援 Escape 關閉' "$READER" "Escape"
require_literal '支援本機儲存' "$READER" "localStorage"
require_literal '會量測固定底部快捷列' "$READER" ".bf-bottom-nav"
require_regex 'CSS 定義三段讀者級別' "$FOUNDATION" 'data-fw-reader-size="large"[\s\S]{0,1400}data-fw-reader-size="xlarge"'
require_regex 'Aa 是 56px 真正圓形控制' "$FOUNDATION" '\.fw-reader-trigger[\s\S]{0,320}width:\s*56px[\s\S]{0,180}height:\s*56px[\s\S]{0,520}border-radius:\s*50%'
require_regex '面板使用 14px 與浮層陰影' "$FOUNDATION" '\.fw-reader-panel[\s\S]{0,500}border-radius:\s*var\(--fw-radius-card\)[\s\S]{0,220}box-shadow:\s*var\(--fw-shadow-popover\)'
require_regex 'functions enqueue 閱讀器控制' "$FUNCTIONS" "wp_enqueue_script\(\s*'fwdignity-reader-size'"
require_literal '資產版本 3.3.0' "$FUNCTIONS" "define( 'FWDIGNITY_VERSION', '3.3.0' );"
# 2026-07-17 W2-C：Serif 900 全站零渲染（標題淨層一律 700），自載入 URL 剔除；Sans 900 保留給品牌標誌例外。
require_literal 'Google Fonts 只載核准 Noto 字重' "$FUNCTIONS" 'family=Noto+Sans+TC:wght@400;500;700;900&family=Noto+Serif+TC:wght@700&display=swap'
forbid_regex '不再載入 Lora' "$FUNCTIONS" 'fonts\.googleapis\.com[^\n]*Lora'

printf '\n結果：%d PASS / %d FAIL\n' "$passes" "$failures"
if (( failures > 0 )); then
  exit 1
fi
