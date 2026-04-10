#!/usr/bin/env bash
# deploy.sh - 伺服器端自動部署腳本
# 在 Hostinger 伺服器上執行，將檔案複製到正確的 WordPress 目錄
#
# 使用方式：
#   WP_PATH=/home/u123456789/public_html bash scripts/deploy.sh
#
# 環境變數（可在執行時傳入，或修改下方預設值）：
#   WP_PATH  - WordPress 安裝根目錄（預設: ~/public_html）
#   THEME    - Astra child theme 資料夾名稱（預設: astra-child）

set -e  # 任何步驟失敗就立即停止

export PATH="$HOME/bin:$PATH"
WP_PATH="${WP_PATH:-$HOME/domains/fwdignity.com/public_html}"
THEME="${THEME:-astra-child}"
THEME_PATH="$WP_PATH/wp-content/themes/$THEME"
MU_PLUGINS="$WP_PATH/wp-content/mu-plugins"

echo "======================================"
echo "  尊嚴農業 WordPress 自動部署腳本"
echo "======================================"
echo "  WP 路徑：$WP_PATH"
echo "  主題路徑：$THEME_PATH"
echo "  MU-Plugins：$MU_PLUGINS"
echo "--------------------------------------"

# 確認目標目錄存在
if [ ! -d "$THEME_PATH" ]; then
    echo "❌ 錯誤：找不到 Astra child theme 目錄：$THEME_PATH"
    echo "   請先建立 child theme，或修改 THEME 環境變數"
    exit 1
fi

if [ ! -d "$MU_PLUGINS" ]; then
    echo "→ 建立 mu-plugins 目錄..."
    mkdir -p "$MU_PLUGINS"
fi

# 0. 部署前覆蓋檢查（避免「有檔案但不會上線」）
# 模式：warn（預設，提示但不中止）/ strict（發現未映射 html 直接中止）/ off（關閉檢查）
COVERAGE_MODE="${DEPLOY_COVERAGE_MODE:-warn}"
if [ "$COVERAGE_MODE" != "off" ] && [ -f "scripts/audit-page-coverage.sh" ]; then
    echo "→ [0/4] 部署前覆蓋檢查（模式：$COVERAGE_MODE）..."
    if ! command -v python3 &>/dev/null; then
        echo "   ⚠️  python3 未安裝，跳過覆蓋檢查（本地開發時請安裝 python3 以啟用）"
    else
        COVERAGE_JSON="$(bash scripts/audit-page-coverage.sh --json)"
        COVERAGE_JSON="$COVERAGE_JSON" python3 - "$COVERAGE_MODE" <<'PY'
import json
import os
import sys

mode = sys.argv[1]
ignore_html = {
    "pages/shared/sidebar.html",  # 共用片段，不是獨立頁面
}

try:
    report = json.loads(os.environ.get("COVERAGE_JSON", ""))
except Exception as exc:
    print(f"❌ 覆蓋檢查失敗：無法解析 audit JSON（{exc}）")
    sys.exit(2)

mapped_missing = report.get("mapped_missing_or_empty", [])
unmapped_html = [
    p for p in report.get("unmapped_non_empty_html", [])
    if p not in ignore_html
]

if mapped_missing:
    print("❌ page-map 內有不存在或空白來源，請先修正：")
    for item in mapped_missing:
        print(f"   - {item}")
    sys.exit(2)

if unmapped_html:
    if mode == "strict":
        print("❌ 發現未映射的非空 .html（strict 模式中止部署）：")
        for item in unmapped_html:
            print(f"   - {item}")
        print("   請補 page ID 並更新 scripts/page-map.json 後重試。")
        sys.exit(3)

    print("⚠️  發現未映射的非空 .html（warn 模式僅提示）：")
    for item in unmapped_html:
        print(f"   - {item}")
    print("   提醒：補 page ID 後加入 scripts/page-map.json。")
else:
    print("   ✓ 覆蓋檢查通過：可部署 .html 均已映射")
PY
    fi
else
    echo "→ [0/4] 略過覆蓋檢查（DEPLOY_COVERAGE_MODE=off 或找不到 audit 腳本）"
fi

# 1. 部署 CSS
echo "→ [1/4] 部署 global.css..."
cp css/global.css "$THEME_PATH/global.css"
echo "   ✓ 複製到 $THEME_PATH/global.css"

# 同時部署 design system v2.0 (global.new.css)
# 新首頁 (pages/home/index.html) 跟其他用 design system v2.0 的頁面會引用這個檔
if [ -f "css/global.new.css" ]; then
    cp css/global.new.css "$THEME_PATH/global.new.css"
    echo "   ✓ 複製到 $THEME_PATH/global.new.css (design system v2.0)"
fi

# 1b. 部署 JS 資產（GSAP 本地 bundle，避免 CDN 依賴）
echo "→ [2/4] 部署 assets/js/..."
if [ -d "assets/js" ]; then
    mkdir -p "$THEME_PATH/assets/js"
    cp assets/js/*.js "$THEME_PATH/assets/js/" 2>/dev/null || true
    [ -f "assets/js/README.md" ] && cp assets/js/README.md "$THEME_PATH/assets/js/"
    echo "   ✓ 複製 $(ls assets/js/*.js 2>/dev/null | wc -l | tr -d ' ') 個 JS 檔到 $THEME_PATH/assets/js/"
fi

# 確認 child theme functions.php 有載入 CSS
if [ -f "$THEME_PATH/functions.php" ]; then
    if ! grep -q "global-css" "$THEME_PATH/functions.php"; then
        echo "⚠️  提醒：請確認 functions.php 有 wp_enqueue_style 載入 global.css"
        echo "   參考：wp_enqueue_style('global-css', get_stylesheet_directory_uri() . '/global.css');"
    fi
fi

# 2. PHP Shortcodes
# 大部分 shortcodes 由 WordPress「Code Snippets」外掛管理，不需複製到 mu-plugins。
# 但以下自動抓取 shortcodes 透過 mu-plugins 部署（不在 Code Snippets 中，不會衝突）。
echo "→ [3/4] PHP shortcodes..."

# 需要部署到 mu-plugins 的 shortcode 清單（RSS 自動抓取類）
MU_SHORTCODES="farmer_courses.php farmer_videos.php"
for sc in $MU_SHORTCODES; do
    if [ -f "shortcodes/$sc" ]; then
        cp "shortcodes/$sc" "$MU_PLUGINS/$sc"
        echo "   ✓ 部署 $sc → mu-plugins"
    fi
done

# 清理先前誤部署到 mu-plugins 的舊 shortcode 檔案（Code Snippets 管理的那些）
for f in shortcodes/*.php; do
    base="$(basename "$f")"
    # 跳過本次需要部署的檔案
    case " $MU_SHORTCODES " in *" $base "*) continue ;; esac
    if [ -f "$f" ] && [ -f "$MU_PLUGINS/$base" ]; then
        rm -f -- "$MU_PLUGINS/$base"
        echo "   🗑️  已移除 $MU_PLUGINS/$base"
    fi
done

# 3. 匯入 HTML 頁面（需要 page-map.json 和 WP-CLI）
echo "→ [4/4] 匯入 HTML 頁面..."
if [ ! -f "scripts/page-map.json" ]; then
    echo "   ⚠️  找不到 scripts/page-map.json，跳過頁面匯入"
    echo "   請建立 page-map.json 並填入頁面 ID 後再重新部署"
elif ! command -v wp &>/dev/null; then
    echo "   ⚠️  找不到 WP-CLI 指令，跳過頁面匯入"
    echo "   請先安裝 WP-CLI：https://wp-cli.org/"
else
    bash scripts/import-wp-pages.sh
fi

echo "--------------------------------------"
echo "✅ 部署完成！"
echo "======================================"
