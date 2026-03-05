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

# 用 Python 讀取 JSON 並逐一更新頁面
python3 - <<'PYEOF'
import json
import subprocess
import sys
import os

wp_path = os.environ.get("WP_PATH", os.path.expanduser("~/public_html"))

with open("scripts/page-map.json", "r", encoding="utf-8") as f:
    mapping = json.load(f)

success_count = 0
fail_count = 0

for html_file, page_id in mapping.items():
    if not os.path.isfile(html_file):
        print(f"  ⚠️  找不到檔案：{html_file}，跳過")
        fail_count += 1
        continue

    with open(html_file, "r", encoding="utf-8") as hf:
        content = hf.read()

    # 使用 WP-CLI 更新頁面內容
    result = subprocess.run(
        ["wp", f"--path={wp_path}", "post", "update", str(page_id),
         f"--post_content={content}", "--quiet"],
        capture_output=True,
        text=True
    )

    if result.returncode == 0:
        print(f"  ✓ 頁面 ID {page_id}：{html_file}")
        success_count += 1
    else:
        print(f"  ❌ 頁面 ID {page_id} 更新失敗：{result.stderr.strip()}")
        fail_count += 1

print(f"\n  共更新 {success_count} 頁，失敗 {fail_count} 頁")

if fail_count > 0:
    sys.exit(1)
PYEOF
