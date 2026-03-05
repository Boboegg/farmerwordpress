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

export PATH="$HOME/bin:$PATH"
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

# 將匹配結果寫入暫存檔，避免 pipe subshell 和 process substitution（Hostinger 不支援 /dev/fd）
TMPMAP=$(mktemp)
grep -E '"[^"]+": *[0-9]+' scripts/page-map.json > "$TMPMAP" || true

while IFS= read -r line; do
    # 提取 key（檔案路徑）和頁面 ID
    key=$(echo "$line" | sed -E 's/^[[:space:]]*"([^"]+)".*/\1/')
    # 跳過以 "_" 開頭的說明欄位
    case "$key" in _*) continue ;; esac
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

    # 使用 WP-CLI 更新頁面內容（透過暫存檔傳遞，避免 shell 處理大型 HTML 出問題）
    TMP_CONTENT=$(mktemp)
    cat "$html_file" > "$TMP_CONTENT"
    ERR_FILE=$(mktemp)
    if wp --path="$WP_PATH" post update "$page_id" "$TMP_CONTENT" --quiet 2>"$ERR_FILE"; then
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
    rm -f "$TMP_CONTENT"
done < "$TMPMAP"
rm -f "$TMPMAP"

echo ""
echo "  共更新 $SUCCESS_COUNT 頁，失敗 $FAIL_COUNT 頁"

# =====================================================
# 確保首頁（ID 1420）使用右側邊欄佈局
# Astra 主題透過 post meta 控制側邊欄顯示
# site-sidebar-layout: right-sidebar 表示右側邊欄
# =====================================================
echo ""
echo "→ 設定首頁側邊欄佈局..."
if wp --path="$WP_PATH" post meta update 1420 site-sidebar-layout right-sidebar --quiet 2>/dev/null; then
    echo "  ✓ 首頁已設定為右側邊欄佈局"
else
    echo "  ⚠️  無法設定首頁側邊欄佈局（非致命錯誤）"
fi

if [ "$FAIL_COUNT" -gt 0 ]; then
    exit 1
fi
