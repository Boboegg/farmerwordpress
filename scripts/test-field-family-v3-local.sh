#!/usr/bin/env bash
set -uo pipefail

repo_dir="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
theme_dir="$repo_dir/themes/fwdignity"
base_url="${BASE_URL:-http://fwdignity-local.local}"
php_bin="${PHP:-$HOME/Library/Application Support/Local/lightning-services/php-8.3.30+1/bin/darwin-arm64/bin/php}"
pwcli="${PWCLI:-${CODEX_HOME:-$HOME/.codex}/skills/playwright/scripts/playwright_cli.sh}"
session="field-family-v3-$$"
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

templates=(
    "$theme_dir/page-field-learning.php"
    "$theme_dir/page-field-notes.php"
    "$theme_dir/page-farmer-study.php"
    "$theme_dir/page-field-events.php"
)

stylesheets=(
    "$theme_dir/assets/css/page-field-learning.css"
    "$theme_dir/assets/css/page-field-notes.css"
    "$theme_dir/assets/css/page-farmer-study.css"
    "$theme_dir/assets/css/page-field-events.css"
)

javascript="$theme_dir/assets/js/page-field-notes.js"

fail() {
    printf 'FAIL: %s\n' "$1" >&2
    failures=$((failures + 1))
}

pass() {
    printf 'PASS: %s\n' "$1"
}

assert_contains() {
    local file="$1"
    local needle="$2"
    local message="$3"

    if grep -Fq -- "$needle" "$file"; then
        pass "$message"
    else
        fail "$message"
    fi
}

assert_not_contains() {
    local file="$1"
    local needle="$2"
    local message="$3"

    if grep -Fq -- "$needle" "$file"; then
        fail "$message"
    else
        pass "$message"
    fi
}

for file in "${templates[@]}" "${stylesheets[@]}" "$javascript"; do
    if [ -f "$file" ]; then
        pass "檔案存在：${file#"$repo_dir/"}"
    else
        fail "找不到檔案：${file#"$repo_dir/"}"
    fi
done

learning_template="${templates[0]}"
notes_template="${templates[1]}"
study_template="${templates[2]}"
events_template="${templates[3]}"

assert_contains "$learning_template" '<h1><?php the_title(); ?></h1>' '田野誌 H1 由 WordPress 頁面標題輸出'
assert_contains "$notes_template" '<h1><?php the_title(); ?></h1>' '田野紀錄 H1 由 WordPress 頁面標題輸出'
assert_contains "$study_template" '<h1><?php the_title(); ?></h1>' '農學堂 H1 由 WordPress 頁面標題輸出'
assert_contains "$events_template" '<h1><?php the_title(); ?></h1>' '活動預留頁 H1 由 WordPress 頁面標題輸出'

assert_not_contains "$learning_template" 'field-family-pathways' '田野誌已移除舊三入口框'
assert_contains "$learning_template" '/field-notes/' '田野誌連到田野紀錄'
assert_contains "$learning_template" '/farmer-study/' '田野誌連到農學堂'
assert_not_contains "$learning_template" '/field-events/' '主頁不把未公開活動列為主要入口'

assert_contains "$learning_template" 'field-record-card--text-only' '田野與培力缺圖文章使用緊湊文字版'
assert_contains "$learning_template" 'field-featured--text-only' '田野與培力精選缺圖時不保留空媒體框'
assert_contains "$notes_template" 'field-note-card--text-only' '田野誌缺圖文章使用緊湊文字版'
assert_contains "$study_template" 'training-record--text-only' '培力紀錄缺圖文章使用緊湊文字版'

assert_not_contains "$events_template" '$recap_query' '活動預留頁不以培力文章冒充活動回顧'
assert_not_contains "$events_template" 'field-events-recap-band' '活動預留頁移除大型回顧帶'
assert_contains "$events_template" 'field-events-empty' '活動預留頁保留無公開活動狀態'

for css in "${stylesheets[@]}"; do
    assert_contains "$css" 'var(--fw-color-page)' "${css##*/} 使用清朗田野頁面色"
    assert_contains "$css" 'var(--fw-color-surface)' "${css##*/} 使用清朗田野內容面色"
    assert_contains "$css" 'var(--fw-color-primary)' "${css##*/} 使用清朗田野主色"
    if grep -Eq '#[[:xdigit:]]{3,8}|rgba?\(' "$css"; then
        fail "${css##*/} 不得新增私有 hex/rgb 色彩"
    else
        pass "${css##*/} 僅引用現役色彩 token"
    fi
done

# 2026-07-17 Phase D 批②：明亮化治理規則（background: page）自 foundation 下沉至本檔，
# 字面禁令改為意圖檢查——檔內任何 .training-dark 規則必須是明亮化（背景=page），不得回到深色。
if awk 'BEGIN { RS = "}"; bad = 0 }
    /\.training-dark/ && /background:/ && !/background:[[:space:]]*var\(--fw-color-page\)/ { bad = 1 }
    END { exit bad ? 1 : 0 }' "${stylesheets[2]}"; then
    pass '培力頁不再使用大型深色影音區（僅存明亮化治理規則）'
else
    fail '培力頁不再使用大型深色影音區'
fi
assert_contains "${stylesheets[0]}" 'aspect-ratio:' '田野與培力圖片有穩定比例'
assert_contains "${stylesheets[1]}" 'aspect-ratio:' '田野誌圖片有穩定比例'
assert_contains "${stylesheets[2]}" 'aspect-ratio:' '培力紀錄圖片有穩定比例'

if [ -x "$php_bin" ]; then
    for template in "${templates[@]}"; do
        if "$php_bin" -l "$template" >/dev/null; then
            pass "PHP 語法通過：${template##*/}"
        else
            fail "PHP 語法失敗：${template##*/}"
        fi
    done

    for css in "${stylesheets[@]}"; do
        if "$php_bin" -r '
            $css = file_get_contents( $argv[1] );
            exit( substr_count( $css, "{" ) === substr_count( $css, "}" ) ? 0 : 1 );
        ' "$css"; then
            pass "CSS 大括號平衡：${css##*/}"
        else
            fail "CSS 大括號不平衡：${css##*/}"
        fi
    done
else
    fail "找不到 PHP：$php_bin"
fi

if command -v node >/dev/null 2>&1 && node --check "$javascript" >/dev/null; then
    pass '田野誌 JavaScript 語法通過'
else
    fail '田野誌 JavaScript 語法失敗或找不到 node'
fi

for path in field-learning field-notes farmer-study; do
    html="$(curl --silent --show-error --fail --location "$base_url/$path/" 2>/dev/null || true)"
    if [ -z "$html" ]; then
        fail "HTTP 無法讀取 $base_url/$path/"
        continue
    fi
    if grep -Fq 'id="main"' <<<"$html" && grep -Fq '<h1>' <<<"$html"; then
        pass "HTTP 頁面可讀且有主內容：/$path/"
    else
        fail "HTTP 頁面缺少主內容或 H1：/$path/"
    fi
done

learning_html="$(curl --silent --show-error --fail --location "$base_url/field-learning/" 2>/dev/null || true)"
for label in '田野誌' '田野紀錄' '農學堂' '做農的人'; do
    if grep -Fq "$label" <<<"$learning_html"; then
        pass "HTTP 主頁可見入口：$label"
    else
        fail "HTTP 主頁缺少入口：$label"
    fi
done

events_status="$(curl --silent --show-error --location --output /tmp/fwdignity-field-events-$$.html --write-out '%{http_code}' "$base_url/field-events/" 2>/dev/null || true)"
if [ "$events_status" = '404' ]; then
    pass '活動頁維持未公開，不修改 DB 或線上路由'
elif [ "$events_status" = '200' ]; then
    events_html="$(cat /tmp/fwdignity-field-events-$$.html)"
    if grep -Fq 'field-events-recap-band' <<<"$events_html"; then
        fail '公開活動頁不得出現大型活動回顧帶'
    else
        pass '公開活動頁沒有以舊內容充當活動主角'
    fi
else
    fail "活動頁 HTTP 狀態異常：${events_status:-無回應}"
fi
rm -f /tmp/fwdignity-field-events-$$.html

if [ ! -x "$pwcli" ]; then
    fail "找不到 Playwright CLI：$pwcli"
elif ! command -v npx >/dev/null 2>&1; then
    fail 'Playwright CLI 需要 npx'
elif "$pwcli" -s="$session" --config "$config" open "$base_url/field-learning/" >/dev/null 2>&1; then
    browser_result="$("$pwcli" -s="$session" --raw run-code 'async (page) => {
        const paths = ["/field-learning/", "/field-notes/", "/farmer-study/"];
        for (const width of [1440, 1025]) {
            await page.setViewportSize({ width, height: 1000 });
            for (const path of paths) {
                const response = await page.goto("http://fwdignity-local.local" + path, { waitUntil: "domcontentloaded" });
                await page.waitForTimeout(250);
                if (!response || response.status() !== 200) return false;
                const valid = await page.evaluate(({ path, width }) => {
                    const root = document.documentElement;
                    const h1 = [...document.querySelectorAll("main h1")].filter((node) => {
                        const rect = node.getBoundingClientRect();
                        return rect.width > 0 && rect.height > 0;
                    });
                    if (root.scrollWidth > root.clientWidth + 1 || h1.length !== 1) return false;

                    if (path === "/field-learning/") {
                        if (document.querySelectorAll(".field-family-pathways").length !== 0) return false;
                        if (!document.querySelector(".field-section") || !document.querySelector(".field-index-band")) return false;
                    }

                    const visualCards = [...document.querySelectorAll(
                        ".field-record-card:not(.field-record-card--text-only), " +
                        ".field-notes-grid .field-note-card:not(.field-note-card--text-only), " +
                        ".training-record:not(.training-record--text-only)"
                    )];
                    for (const card of visualCards) {
                        const media = card.querySelector(".field-record-media, .field-note-card__media, .training-record__media");
                        const copy = card.querySelector(".field-record-body, .field-note-card__body, .training-record__body");
                        if (!media || !copy) return false;
                        const mediaRect = media.getBoundingClientRect();
                        const copyRect = copy.getBoundingClientRect();
                        const ratio = mediaRect.width / mediaRect.height;
                        const overlaps = mediaRect.left < copyRect.right - 1 &&
                            mediaRect.right > copyRect.left + 1 &&
                            mediaRect.top < copyRect.bottom - 1 &&
                            mediaRect.bottom > copyRect.top + 1;
                        if (overlaps || ratio < 1.45 || ratio > 1.8) return false;
                    }

                    const textCards = [...document.querySelectorAll(
                        ".field-record-card--text-only, .field-note-card--text-only, .training-record--text-only"
                    )];
                    if (textCards.some((card) => card.querySelector("img, .field-record-media, .field-note-card__media, .training-record__media"))) {
                        return false;
                    }

                    const controls = [...document.querySelectorAll("button, .field-notes-search input, .field-notes-filter-select")]
                        .filter((control) => {
                            const style = getComputedStyle(control);
                            const rect = control.getBoundingClientRect();
                            return style.display !== "none" && style.visibility !== "hidden" && rect.width > 0 && rect.height > 0;
                        });
                    return controls.every((control) => control.getBoundingClientRect().height >= 43) && width === root.clientWidth;
                }, { path, width });
                if (!valid) return false;
            }
        }

        await page.setViewportSize({ width: 1025, height: 1000 });
        await page.goto("http://fwdignity-local.local/field-notes/", { waitUntil: "domcontentloaded" });
        const timelineButton = page.locator("[data-field-view=timeline]");
        await timelineButton.click();
        const timelineVisible = await page.locator("[data-field-panel=timeline]").isVisible();
        return timelineVisible;
    }' 2>/dev/null || true)"
    if [ "$browser_result" = 'true' ]; then
        pass 'Playwright 1440px／1025px：三個現役頁無溢位、圖文分離且田野誌切換可用'
    else
        fail 'Playwright 1440px／1025px 田野頁群版面契約失敗'
    fi
else
    fail "Playwright 無法開啟 $base_url/field-learning/"
fi

if [ "$failures" -gt 0 ]; then
    printf '\nfield family v3 checks failed: %d\n' "$failures" >&2
    exit 1
fi

printf '\nfield family v3 checks passed\n'
