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

set -eo pipefail

WP_PATH="${WP_PATH:-$HOME/domains/fwdignity.com/public_html}"

echo "→ 開始匯入 HTML 頁面到 WordPress..."
echo "  WP 路徑：$WP_PATH"

if ! command -v wp &>/dev/null; then
    echo "  ⚠️  找不到 WP-CLI，跳過頁面匯入"
    exit 0
fi

if [ ! -f "scripts/page-map.json" ]; then
    echo "  ⚠️  找不到 scripts/page-map.json，跳過頁面匯入"
    exit 0
fi

SUCCESS_COUNT=0
FAIL_COUNT=0

# 用 process substitution 避免 subshell（計數器才能正確累加）
while IFS= read -r line; do
    # 提取 key（檔案路徑）和頁面 ID
    key=$(echo "$line" | sed -E 's/^[[:space:]]*"([^"]+)".*/\1/')
    # 跳過以 "_" 開頭的說明欄位
    if [[ "$key" == _* ]]; then
        continue
    fi
    html_file="$key"
    page_id=$(echo "$line" | sed -E 's/.*: *([0-9]+).*/\1/')

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
    ERR_FILE=$(mktemp)
    if wp --path="$WP_PATH" post update "$page_id" --post_content="$CONTENT" --quiet 2>"$ERR_FILE"; then
        echo "  ✓ 頁面 ID $page_id：$html_file"
        SUCCESS_COUNT=$((SUCCESS_COUNT + 1))
        rm -f "$ERR_FILE"
    else
        echo "  ❌ 頁面 ID $page_id 更新失敗：$html_file"
        if [ -s "$ERR_FILE" ]; then
            echo "     WP-CLI 錯誤訊息："
            cat "$ERR_FILE"
        fi
        rm -f "$ERR_FILE"
        FAIL_COUNT=$((FAIL_COUNT + 1))
    fi
done < <(grep -E '"[^"]+": *[0-9]+' scripts/page-map.json)

echo ""
echo "  共更新 $SUCCESS_COUNT 頁，失敗 $FAIL_COUNT 頁"

if [ "$FAIL_COUNT" -gt 0 ]; then
    exit 1
fi
