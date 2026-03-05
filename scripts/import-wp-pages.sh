#!/usr/bin/env bash
# import-wp-pages.sh - 使用 WP-CLI 將 HTML 檔案內容匯入 WordPress 頁面
#
# 需求：
#   - WP-CLI 已安裝（執行 `wp --info` 確認）
#   - scripts/page-map.json 存在（HTML 檔案路徑 → WordPress 頁面 ID）
#   - WP_PATH 環境變數已設定
#
# page-map.json 格式：
#   {
#     "pages/home/index.html": 123,
#     "pages/About/關於計畫.html": 456
#   }

set -e

WP_PATH="${WP_PATH:-$HOME/domains/fwdignity.com/public_html}"

echo "→ 開始匯入 HTML 頁面到 WordPress..."
echo "  WP 路徑：$WP_PATH"

if ! command -v wp &>/dev/null; then
    echo "  ⚠️  找不到 WP-CLI，跳過頁面匯入"
    exit 0
fi

SUCCESS_COUNT=0
FAIL_COUNT=0

# 用 grep 從 page-map.json 解析 "file": id 格式的行（純 bash，不需要 python）
grep -E '"pages/.*":' scripts/page-map.json | while IFS= read -r line; do
    # 提取檔案路徑和頁面 ID
    html_file=$(echo "$line" | sed 's/.*"\(pages\/[^"]*\)".*/\1/')
    page_id=$(echo "$line" | sed 's/.*: *\([0-9]*\).*/\1/')

    # 跳過 ID 為 0 的項目（尚未設定）
    if [ "$page_id" = "0" ]; then
        echo "  ⏭️  跳過 $html_file（頁面 ID 尚未設定）"
        continue
    fi

    if [ ! -f "$html_file" ]; then
        echo "  ⚠️  找不到檔案：$html_file，跳過"
        FAIL_COUNT=$((FAIL_COUNT + 1))
        continue
    fi

    # 使用 WP-CLI 更新頁面內容
    CONTENT=$(cat "$html_file")
    if wp --path="$WP_PATH" post update "$page_id" --post_content="$CONTENT" --quiet 2>/dev/null; then
        echo "  ✓ 頁面 ID $page_id：$html_file"
        SUCCESS_COUNT=$((SUCCESS_COUNT + 1))
    else
        echo "  ❌ 頁面 ID $page_id 更新失敗：$html_file"
        FAIL_COUNT=$((FAIL_COUNT + 1))
    fi
done

echo ""
echo "  頁面匯入完成"
