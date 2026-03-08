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
#     "pages/About/關於我們.html": 456
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
echo "→ 設定頁面側邊欄佈局..."

# 需要右側邊欄的頁面 ID 列表
SIDEBAR_PAGES=(1420 1476)  # 首頁、關於我們

for page_id in "${SIDEBAR_PAGES[@]}"; do
    if wp --path="$WP_PATH" post meta update "$page_id" site-sidebar-layout right-sidebar --quiet 2>/dev/null; then
        echo "  ✓ 頁面 ID $page_id 已設定為右側邊欄佈局"
    else
        echo "  ⚠️  無法設定頁面 ID $page_id 側邊欄佈局（非致命錯誤）"
    fi
done

# =====================================================
# 匯入側邊欄模組到 WordPress Widget 區域
# 使用 WP-CLI widget 指令（相容 Classic & Block Widget）
# =====================================================
echo ""
echo "→ 匯入側邊欄 Widget..."

# 自動偵測 Astra 側邊欄 ID
SIDEBAR_ID=""
for try_id in "sidebar-1" "astra-sidebar" "primary-sidebar"; do
    if wp --path="$WP_PATH" sidebar list --format=ids 2>/dev/null | grep -qw "$try_id"; then
        SIDEBAR_ID="$try_id"
        break
    fi
done

# 如果自動偵測失敗，列出所有可用的 sidebar
if [ -z "$SIDEBAR_ID" ]; then
    echo "  ⚠️  無法自動偵測側邊欄 ID，嘗試列出所有 sidebar..."
    AVAILABLE=$(wp --path="$WP_PATH" sidebar list --fields=id,name --format=table 2>/dev/null || echo "")
    if [ -n "$AVAILABLE" ]; then
        echo "$AVAILABLE"
    fi
    # 回退使用 sidebar-1
    SIDEBAR_ID="sidebar-1"
    echo "  使用預設值：$SIDEBAR_ID"
fi

echo "  使用側邊欄 ID：$SIDEBAR_ID"

# 側邊欄模組檔案（按顯示順序排列）
SIDEBAR_MODULES=(
    "pages/home/分眾入口"
    "pages/home/快速連結"
    "pages/home/社群媒體"
    "pages/home/側邊欄工具箱"
    "pages/home/熱門關鍵字"
    "pages/home/友善連結"
)

# 先清除既有的 custom_html widgets（避免重複）
echo "  清除既有側邊欄 Widget..."
EXISTING_WIDGETS=$(wp --path="$WP_PATH" widget list "$SIDEBAR_ID" --format=ids 2>/dev/null || echo "")
if [ -n "$EXISTING_WIDGETS" ]; then
    for widget_id in $EXISTING_WIDGETS; do
        wp --path="$WP_PATH" widget delete "$widget_id" --quiet 2>/dev/null || true
    done
    echo "  ✓ 已清除舊 Widget"
else
    echo "  （側邊欄目前沒有 Widget）"
fi

# 逐一匯入每個模組為獨立的 Custom HTML Widget
# 使用 WP-CLI 的 wp widget add 指令（官方支援，相容所有 Widget 模式）
WIDGET_COUNT=0
for module_file in "${SIDEBAR_MODULES[@]}"; do
    if [ ! -f "$module_file" ]; then
        echo "  ⚠️  找不到模組：$module_file，跳過"
        continue
    fi

    module_name=$(basename "$module_file")

    # 剝掉 <style>...</style> 區塊（CSS 已統一放在 css/global.css）
    TMP_WIDGET=$(mktemp)
    sed '/<style>/,/<\/style>/d' "$module_file" > "$TMP_WIDGET"

    # 讀取清理後的 HTML 內容
    WIDGET_CONTENT=$(cat "$TMP_WIDGET")
    rm -f "$TMP_WIDGET"

    if [ -z "$WIDGET_CONTENT" ]; then
        echo "  ⚠️  模組內容為空：$module_name，跳過"
        continue
    fi

    # 使用 WP-CLI widget add 指令匯入
    # --position 確保按照順序排列
    ERR_LOG=$(mktemp)
    if wp --path="$WP_PATH" widget add custom_html "$SIDEBAR_ID" --content="$WIDGET_CONTENT" --position=$((WIDGET_COUNT + 1)) 2>"$ERR_LOG"; then
        echo "  ✓ Widget：$module_name"
        WIDGET_COUNT=$((WIDGET_COUNT + 1))
    else
        echo "  ❌ Widget 匯入失敗：$module_name"
        if [ -s "$ERR_LOG" ]; then
            echo "     錯誤訊息："
            cat "$ERR_LOG"
        fi
        # 回退方案：使用 PHP eval-file 直接寫入 option（舊方法）
        echo "  ↻ 嘗試回退方案..."
        IMPORT_PHP=$(mktemp)
        TMP_WIDGET2=$(mktemp)
        # 重用已處理的內容（避免重複 sed 處理）
        printf '%s' "$WIDGET_CONTENT" > "$TMP_WIDGET2"
        cat > "$IMPORT_PHP" <<'PHPEOF'
<?php
$sidebar_id = $argv[1];
$content_file = $argv[2];
$content = file_get_contents($content_file);
if (empty(trim($content))) { echo "內容為空\n"; exit(1); }

// 取得現有的 custom_html widget 資料
$widgets = get_option('widget_custom_html', array());
if (!is_array($widgets)) $widgets = array();

// 確保 _multiwidget 標記存在
$widgets['_multiwidget'] = 1;

// 找到下一個可用的 widget index
$max_idx = 0;
foreach ($widgets as $k => $v) {
    if (is_numeric($k) && $k > $max_idx) $max_idx = (int)$k;
}
$new_idx = $max_idx + 1;

// 新增 widget
$widgets[$new_idx] = array('title' => '', 'content' => $content);
update_option('widget_custom_html', $widgets);

// 將 widget 加入側邊欄
$sidebars = get_option('sidebars_widgets', array());
if (!is_array($sidebars)) $sidebars = array();
if (!isset($sidebars[$sidebar_id]) || !is_array($sidebars[$sidebar_id])) {
    $sidebars[$sidebar_id] = array();
}
$sidebars[$sidebar_id][] = 'custom_html-' . $new_idx;
update_option('sidebars_widgets', $sidebars);
PHPEOF
        if wp --path="$WP_PATH" eval-file "$IMPORT_PHP" "$SIDEBAR_ID" "$TMP_WIDGET2" 2>/dev/null; then
            echo "  ✓ Widget（回退）：$module_name"
            WIDGET_COUNT=$((WIDGET_COUNT + 1))
        else
            echo "  ❌ 回退方案也失敗：$module_name"
        fi
        rm -f "$IMPORT_PHP" "$TMP_WIDGET2"
    fi
    rm -f "$ERR_LOG"
done

echo "  共匯入 $WIDGET_COUNT 個側邊欄 Widget"

# 清除 WordPress 物件快取，確保前台立即生效
wp --path="$WP_PATH" cache flush --quiet 2>/dev/null || true
echo "  ✓ 已清除 WordPress 快取"

if [ "$FAIL_COUNT" -gt 0 ]; then
    exit 1
fi
