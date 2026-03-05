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

# 1. 部署 CSS
echo "→ [1/3] 部署 global.css..."
cp css/global.css "$THEME_PATH/global.css"
echo "   ✓ 複製到 $THEME_PATH/global.css"

# 確認 child theme functions.php 有載入 CSS
if [ -f "$THEME_PATH/functions.php" ]; then
    if ! grep -q "global-css" "$THEME_PATH/functions.php"; then
        echo "⚠️  提醒：請確認 functions.php 有 wp_enqueue_style 載入 global.css"
        echo "   參考：wp_enqueue_style('global-css', get_stylesheet_directory_uri() . '/global.css');"
    fi
fi

# 2. PHP Shortcodes
# 注意：shortcodes 目前由 WordPress「Code Snippets」外掛管理，
# 不需要複製到 mu-plugins（否則會造成函數重複宣告，導致 500 錯誤）。
# 如果日後要改用 mu-plugins 部署，請先停用 Code Snippets 中對應的程式碼片段。
echo "→ [2/3] PHP shortcodes..."
echo "   ⏭️  跳過（由 Code Snippets 外掛管理，避免重複載入）"
# 清理先前誤部署到 mu-plugins 的 shortcode 檔案
for f in shortcodes/*.php; do
    if [ -f "$f" ] && [ -f "$MU_PLUGINS/$(basename "$f")" ]; then
        rm "$MU_PLUGINS/$(basename "$f")"
        echo "   🗑️  已移除 $MU_PLUGINS/$(basename "$f")"
    fi
done

# 3. 匯入 HTML 頁面（需要 page-map.json 和 WP-CLI）
echo "→ [3/3] 匯入 HTML 頁面..."
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
