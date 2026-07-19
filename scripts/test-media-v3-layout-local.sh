#!/usr/bin/env bash
set -uo pipefail

repo_dir="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
template="$repo_dir/themes/fwdignity/page-podcast.php"
stylesheet="$repo_dir/themes/fwdignity/assets/css/page-media-hub.css"
base_url="${BASE_URL:-http://fwdignity-local.local}"
pwcli="${PWCLI:-${CODEX_HOME:-$HOME/.codex}/skills/playwright/scripts/playwright_cli.sh}"
session="media-v3-layout-$$"
config="$(mktemp)"
output_dir="$(mktemp -d "${TMPDIR:-/tmp}/fwdignity-pw.XXXXXX")"
failures=0

cleanup() {
    "$pwcli" -s="$session" close >/dev/null 2>&1 || true
    rm -f "$config"
    rm -rf -- "$output_dir"
}
trap cleanup EXIT
printf '{"outputDir":"%s","browser":{"contextOptions":{"ignoreHTTPSErrors":true}}}\n' "$output_dir" > "$config"

fail() {
    printf 'FAIL: %s\n' "$1" >&2
    failures=$((failures + 1))
}

pass() {
    printf 'PASS: %s\n' "$1"
}

assert_file_contains() {
    local file="$1"
    local needle="$2"
    local message="$3"

    if grep -Fq -- "$needle" "$file"; then
        pass "$message"
    else
        fail "$message"
    fi
}

[ -f "$template" ] || fail "找不到模板：$template"
[ -f "$stylesheet" ] || fail "找不到樣式：$stylesheet"

if [ -f "$template" ] && [ -f "$stylesheet" ]; then
    assert_file_contains "$template" '<h1><?php the_title(); ?></h1>' 'H1 由 WordPress 頁面標題輸出'
    assert_file_contains "$template" 'mh-video-item--text-only' '最新影片缺圖時使用緊湊文字列'
    assert_file_contains "$template" 'mh-archive-item--text-only' '影音資料庫缺圖時使用緊湊文字列'
    assert_file_contains "$stylesheet" 'grid-template-areas:' '主影片 metadata、標題與操作有明確格線區域'
    assert_file_contains "$stylesheet" '.mh-video-item--text-only' '最新影片文字列有獨立樣式'
    assert_file_contains "$stylesheet" '.mh-archive-item--text-only' '影音資料庫文字列有獨立樣式'
    assert_file_contains "$stylesheet" '.mh-video-main [data-media-video-player] { aspect-ratio: 16 / 9; overflow: hidden; background: var(--mh-soft-line); }' '新增主影片外框只引用既有色彩變數'
fi

html="$(curl --silent --show-error --fail --location "$base_url/podcast/" 2>/dev/null || true)"
if [ -z "$html" ]; then
    fail "HTTP 無法讀取 $base_url/podcast/"
else
    h1="$(printf '%s' "$html" | sed -n 's:.*<h1>\([^<]*\)</h1>.*:\1:p' | head -1)"
    page_title="$(printf '%s' "$html" | sed -n 's:.*<title>\([^<-]*\) - .*:\1:p' | head -1)"
    if [ "$h1" = '聽見田裡的聲音' ] && [ "$h1" = "$page_title" ]; then
        pass 'HTTP H1 與 WordPress 頁面標題一致'
    else
        fail "HTTP H1 應為「聽見田裡的聲音」，目前為「${h1:-空值}」"
    fi
fi

if [ ! -x "$pwcli" ]; then
    fail "找不到 Playwright CLI：$pwcli"
elif ! command -v npx >/dev/null 2>&1; then
    fail 'Playwright CLI 需要 npx'
elif "$pwcli" -s="$session" --config "$config" open "$base_url/podcast/" >/dev/null 2>&1; then
    browser_result="$("$pwcli" -s="$session" --raw run-code 'async (page) => {
        for (const width of [1440, 1025]) {
            await page.setViewportSize({ width, height: 1000 });
            const valid = await page.evaluate((viewportWidth) => {
                const withinViewport = document.documentElement.scrollWidth <= viewportWidth;
                const videoItems = [...document.querySelectorAll(".mh-video-item:not(.mh-video-item--text-only)")];
                const separated = videoItems.every((item) => {
                    const visual = item.querySelector(".mh-video-thumb")?.getBoundingClientRect();
                    const copy = item.querySelector(".mh-video-item-copy")?.getBoundingClientRect();
                    return visual && copy && visual.right <= copy.left + 0.5 &&
                        Math.abs((visual.width / visual.height) - (16 / 9)) < 0.03;
                });
                const copy = document.querySelector(".mh-video-copy");
                if (!copy) return withinViewport && separated;
                const copyRect = copy.getBoundingClientRect();
                const parts = [...copy.children].map((child) => child.getBoundingClientRect());
                const contained = parts.every((part) => part.width > 1 &&
                    part.left >= copyRect.left - 0.5 && part.right <= copyRect.right + 0.5);
                const distinct = parts.every((part, index) => parts.slice(index + 1).every((other) => !(
                    part.left < other.right - 0.5 && part.right > other.left + 0.5 &&
                    part.top < other.bottom - 0.5 && part.bottom > other.top + 0.5
                )));
                return withinViewport && separated && contained && distinct;
            }, width);
            if (!valid) return false;
        }
        return true;
    }' 2>/dev/null || true)"
    if [ "$browser_result" = 'true' ]; then
        pass '1440px/1025px 無水平溢出，影音圖片與內容區不重疊'
    else
        fail '1440px/1025px 影音盒模型驗證失敗'
    fi
else
    fail "Playwright 無法開啟 $base_url/podcast/"
fi

if [ "$failures" -gt 0 ]; then
    printf '\nmedia v3 layout checks failed: %d\n' "$failures" >&2
    exit 1
fi

printf '\nmedia v3 layout checks passed\n'
