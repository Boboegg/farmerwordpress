#!/usr/bin/env bash
set -uo pipefail

ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
THEME_DIR="${THEME_DIR:-$ROOT_DIR/themes/fwdignity}"
BASE_URL="${BASE_URL:-https://fwdignity-local.local}"
PHP_BIN="${PHP_BIN:-$HOME/Library/Application Support/Local/lightning-services/php-8.3.30+1/bin/darwin-arm64/bin/php}"
NODE_PATH="${NODE_PATH:-$HOME/.cache/codex-runtimes/codex-primary-runtime/dependencies/node/node_modules}"
CHROME_PATH="${CHROME_PATH:-/Applications/Google Chrome.app/Contents/MacOS/Google Chrome}"
ARTIFACT_DIR="${ARTIFACT_DIR:-/tmp/news-single-v3-artifacts}"
MODE="${1:-all}"

NEWS_TEMPLATE="$THEME_DIR/page-thenews.php"
NEWS_CSS="$THEME_DIR/assets/css/page-thenews.css"
NEWS_JS="$THEME_DIR/assets/js/page-thenews.js"
SINGLE_TEMPLATE="$THEME_DIR/single.php"
SINGLE_CSS="$THEME_DIR/assets/css/single-post.css"

checks=0
failures=0

pass() {
    checks=$(( checks + 1 ))
    printf 'PASS  %s\n' "$1"
}

fail() {
    checks=$(( checks + 1 ))
    failures=$(( failures + 1 ))
    printf 'FAIL  %s\n' "$1" >&2
}

contains() {
    local needle="$1"
    local file="$2"
    local description="$3"
    if grep -Fq -- "$needle" "$file"; then
        pass "$description"
    else
        fail "$description"
    fi
}

run_static_checks() {
    local missing=0
    local file

    for file in "$NEWS_TEMPLATE" "$NEWS_CSS" "$NEWS_JS" "$SINGLE_TEMPLATE" "$SINGLE_CSS"; do
        if [ ! -f "$file" ]; then
            fail "required file exists: $file"
            missing=1
        fi
    done
    if [ "$missing" -ne 0 ]; then
        return
    fi
    pass 'all news/single production files exist'

    if "$PHP_BIN" -l "$NEWS_TEMPLATE" >/dev/null && "$PHP_BIN" -l "$SINGLE_TEMPLATE" >/dev/null; then
        pass 'news and single PHP syntax'
    else
        fail 'news and single PHP syntax'
    fi

    if node --check "$NEWS_JS" >/dev/null; then
        pass 'news JavaScript syntax'
    else
        fail 'news JavaScript syntax'
    fi

    if CSS_FILES="$NEWS_CSS:$SINGLE_CSS" node <<'NODE'
const fs = require('fs');
const files = process.env.CSS_FILES.split(':');
for (const file of files) {
  const css = fs.readFileSync(file, 'utf8').replace(/\/\*[\s\S]*?\*\//g, '');
  let depth = 0;
  for (const char of css) {
    if (char === '{') depth += 1;
    if (char === '}') depth -= 1;
    if (depth < 0) process.exit(1);
  }
  if (depth !== 0) process.exit(1);
}
NODE
    then
        pass 'news and single CSS braces are balanced'
    else
        fail 'news and single CSS braces are balanced'
    fi

    if grep -Einq '#[0-9a-f]{3,8}|rgba?\(|hsla?\(|color-mix\(' "$NEWS_CSS" "$SINGLE_CSS"; then
        fail 'page CSS contains no literal or mixed colors'
    else
        pass 'page CSS contains no literal or mixed colors'
    fi

    if grep -Einq -- '--(tn|article)-[a-z0-9-]+[[:space:]]*:' "$NEWS_CSS" "$SINGLE_CSS"; then
        fail 'page CSS defines no private color aliases'
    else
        pass 'page CSS defines no private color aliases'
    fi

    if grep -Einq 'var\(--(tn|article)-' "$NEWS_CSS" "$SINGLE_CSS"; then
        fail 'page CSS references only canonical fw tokens'
    else
        pass 'page CSS references only canonical fw tokens'
    fi

    if grep -Einq 'font-size:[[:space:]]*(clamp\(|[0-9])' "$NEWS_CSS" "$SINGLE_CSS"; then
        fail 'page CSS uses only canonical fixed font-size tokens'
    else
        pass 'page CSS uses only canonical fixed font-size tokens'
    fi

    if grep -Einq 'linear-gradient|radial-gradient|letter-spacing:[[:space:]]*-' "$NEWS_CSS" "$SINGLE_CSS"; then
        fail 'page CSS has no gradients or negative letter spacing'
    else
        pass 'page CSS has no gradients or negative letter spacing'
    fi

    for token in \
        fw-color-page fw-color-paper fw-color-surface fw-color-surface-alt \
        fw-color-primary fw-color-primary-deep fw-color-ink fw-color-muted \
        fw-color-link fw-color-link-hover fw-color-line fw-color-focus \
        fw-font-serif fw-font-sans fw-font-size-h1 fw-font-size-h2 \
        fw-font-size-card-title fw-font-size-body fw-font-size-summary \
        fw-font-size-meta fw-control-height fw-radius-card
    do
        if grep -Fq "var(--$token)" "$NEWS_CSS" "$SINGLE_CSS"; then
            pass "canonical token is used: --$token"
        else
            fail "canonical token is used: --$token"
        fi
    done

    if grep -Einq 'h1[^,{]*[>{][^}]*span|\.accent|title[^,{]*span[^,{]*\{[^}]*color' "$NEWS_CSS" "$SINGLE_CSS"; then
        fail 'H1 and title styling is single-color'
    else
        pass 'H1 and title styling is single-color'
    fi

    contains 'new WP_Query' "$NEWS_TEMPLATE" 'news results come from WP_Query'
    contains 'get_the_excerpt' "$NEWS_TEMPLATE" 'news summaries come from WordPress post data'
    contains "get_post_field( 'post_content'" "$NEWS_TEMPLATE" 'news fallback summary comes from canonical post content'
    contains '<h1>全部內容</h1>' "$NEWS_TEMPLATE" 'content index H1 remains the approved single-color text'
    contains '收錄尊嚴農業的消息文章、田野紀錄、Podcast、YouTube、影音與教材；可依內容形式與主題交叉查找。' "$NEWS_TEMPLATE" 'approved news introduction is unchanged'
    contains "the_title( '<h1>', '</h1>' )" "$SINGLE_TEMPLATE" 'single H1 comes from WordPress'
    contains '<?php the_content(); ?>' "$SINGLE_TEMPLATE" 'single body comes from WordPress without rewriting'
    contains "'post__not_in'" "$SINGLE_TEMPLATE" 'single related query excludes the current post'
    contains 'localStorage' "$NEWS_JS" 'news filter collapse state is remembered'
    contains "setAttribute('aria-expanded'" "$NEWS_JS" 'news filter collapse exposes its state'
}

run_browser_checks() {
    local posts_json

    if [ ! -x "$CHROME_PATH" ]; then
        fail "Google Chrome executable exists: $CHROME_PATH"
        return
    fi
    if ! NODE_PATH="$NODE_PATH" node -e "require('playwright')" >/dev/null 2>&1; then
        fail 'bundled Playwright can be required through NODE_PATH'
        return
    fi
    if ! curl --silent --show-error --fail --insecure --location --max-time 20 "$BASE_URL/thenews/" >/dev/null; then
        fail "LocalWP news route is reachable: $BASE_URL/thenews/"
        return
    fi

    posts_json="$(curl --silent --show-error --fail --insecure --max-time 20 \
        "$BASE_URL/wp-json/wp/v2/posts?per_page=100&status=publish&_fields=link" \
        | jq -c '[.[].link]')"
    if [ "$(jq 'length' <<<"$posts_json")" -lt 1 ]; then
        fail 'REST returned published single-post URLs'
        return
    fi

    mkdir -p "$ARTIFACT_DIR"

    if BASE_URL="$BASE_URL" POST_URLS_JSON="$posts_json" CHROME_PATH="$CHROME_PATH" ARTIFACT_DIR="$ARTIFACT_DIR" NODE_PATH="$NODE_PATH" node <<'NODE'
const fs = require('fs');
const path = require('path');
const { chromium } = require('playwright');

const canonicalColors = new Set([
  'rgb(253, 251, 247)', 'rgb(245, 245, 242)', 'rgb(255, 255, 255)',
  'rgb(240, 240, 236)', 'rgb(67, 88, 50)', 'rgb(49, 66, 37)',
  'rgb(40, 49, 33)', 'rgb(86, 97, 85)', 'rgb(26, 26, 26)',
  'rgb(74, 74, 74)', 'rgb(165, 126, 38)', 'rgb(123, 93, 18)',
  'rgb(224, 224, 224)', 'rgb(247, 246, 241)', 'rgb(162, 74, 61)',
  'rgb(37, 51, 30)', 'rgb(220, 228, 216)', 'rgb(232, 236, 227)',
  'rgb(247, 238, 217)', 'rgb(247, 231, 227)', 'rgb(115, 121, 112)',
  'rgb(52, 69, 45)', 'rgb(55, 110, 124)',
  // 現行規範限定的 Podcast 封面接圖色；不是介面色票。
  'rgb(173, 184, 80)'
]);

const baseUrl = process.env.BASE_URL.replace(/\/$/, '');
const postUrls = JSON.parse(process.env.POST_URLS_JSON);
const targets = [
  { kind: 'news', url: `${baseUrl}/thenews/` },
  ...postUrls.map((url, index) => ({ kind: 'single', url, index }))
];
const viewports = [{ width: 1440, height: 900 }, { width: 1025, height: 900 }];
const results = [];
const failures = [];

const compact = (value) => String(value || '').replace(/\s+/g, ' ').trim().slice(0, 140);

(async () => {
  const browser = await chromium.launch({ executablePath: process.env.CHROME_PATH, headless: true });
  try {
    for (const viewport of viewports) {
      const context = await browser.newContext({
        viewport,
        ignoreHTTPSErrors: true,
        reducedMotion: 'reduce'
      });
      await context.route('https://fwdignity.com/wp-content/uploads/**', async (route) => {
        const localUrl = route.request().url().replace('https://fwdignity.com', baseUrl);
        const localResponse = await route.fetch({ url: localUrl });
        await route.fulfill({ response: localResponse });
      });

      for (const target of targets) {
        const page = await context.newPage();
        const consoleErrors = [];
        const pageErrors = [];
        page.on('console', (message) => {
          if (message.type() === 'error') consoleErrors.push(message.text());
        });
        page.on('pageerror', (error) => pageErrors.push(String(error)));

        const response = await page.goto(target.url, { waitUntil: 'domcontentloaded', timeout: 30000 });
        await page.locator('img').evaluateAll((images) => {
          images.forEach((image) => { image.loading = 'eager'; });
        });
        await page.waitForTimeout(600);
        await page.evaluate(async () => {
          const step = Math.max(500, Math.floor(innerHeight * 0.75));
          for (let y = 0; y < document.documentElement.scrollHeight; y += step) {
            scrollTo(0, y);
            await new Promise((resolve) => setTimeout(resolve, 25));
          }
          scrollTo(0, 0);
        });
        await page.evaluate(async () => {
          await Promise.all([...document.images].map((image) => {
            if (image.complete) return Promise.resolve();
            return new Promise((resolve) => {
              const done = () => resolve();
              image.addEventListener('load', done, { once: true });
              image.addEventListener('error', done, { once: true });
              setTimeout(done, 5000);
            });
          }));
        });

        const metrics = await page.evaluate(({ kind, allowedColors }) => {
          const allowed = new Set(allowedColors);
          const ignored = new Set(['SCRIPT', 'STYLE', 'NOSCRIPT', 'TEMPLATE', 'SVG', 'PATH']);
          const visible = (element) => {
            const style = getComputedStyle(element);
            const rect = element.getBoundingClientRect();
            return style.display !== 'none' && style.visibility !== 'hidden'
              && Number.parseFloat(style.opacity || '1') > 0 && rect.width > 0 && rect.height > 0;
          };
          const ownText = (element) => [...element.childNodes]
            .filter((node) => node.nodeType === Node.TEXT_NODE)
            .map((node) => node.textContent || '').join(' ').replace(/\s+/g, ' ').trim();
          const compactText = (value) => String(value || '').replace(/\s+/g, ' ').trim().slice(0, 140);
          const selectorFor = (element) => element.id
            ? `${element.tagName.toLowerCase()}#${CSS.escape(element.id)}`
            : `${element.tagName.toLowerCase()}${[...element.classList].slice(0, 3).map((name) => `.${CSS.escape(name)}`).join('')}`;
          const all = [...document.querySelectorAll('body *')].filter((element) => !ignored.has(element.tagName));
          const root = document.querySelector(kind === 'news' ? '.tnews-page' : '.article-shell');
          const h1s = [...document.querySelectorAll('h1')].filter(visible);
          const smallText = all.filter((element) => visible(element) && ownText(element))
            .map((element) => ({ selector: selectorFor(element), text: compactText(ownText(element)), size: Number.parseFloat(getComputedStyle(element).fontSize) }))
            .filter((item) => Number.isFinite(item.size) && item.size < 15);
          const controls = [...document.querySelectorAll([
            'button', 'input:not([type="hidden"])', 'select', 'textarea', 'summary', '[role="button"]',
            'a[class*="button"]', 'a[class*="btn"]', 'a[class*="cta"]',
            '.fw-navbar a', '.fw-navbar button', 'footer a'
          ].join(','))].filter(visible);
          const undersized = controls.map((element) => {
            const rect = element.getBoundingClientRect();
            return { selector: selectorFor(element), width: Number(rect.width.toFixed(1)), height: Number(rect.height.toFixed(1)) };
          }).filter((item) => item.width < 44 || item.height < 44);
          const unexpectedColors = [];
          if (root) {
            for (const element of [root, ...root.querySelectorAll('*')].filter(visible)) {
              const style = getComputedStyle(element);
              for (const [role, value] of [['background', style.backgroundColor], ['text', ownText(element) ? style.color : 'transparent']]) {
                if (!value || value === 'transparent' || value === 'rgba(0, 0, 0, 0)') continue;
                const rgb = value.match(/^rgba?\(\s*(\d+)\s*[, ]\s*(\d+)\s*[, ]\s*(\d+)/i);
                const normalized = rgb ? `rgb(${rgb[1]}, ${rgb[2]}, ${rgb[3]})` : value;
                if (!allowed.has(normalized)) unexpectedColors.push({ role, value, selector: selectorFor(element), text: compactText(ownText(element)) });
              }
            }
          }
          const brokenImages = [...document.images].filter(visible)
            .filter((image) => !image.complete || image.naturalWidth === 0)
            .map((image) => ({
              selector: selectorFor(image),
              src: image.currentSrc || image.src,
              alt: compactText(image.getAttribute('alt'))
            }));
          const externalImageFallbacks = brokenImages.filter((image) => {
            try {
              return new URL(image.src).hostname === 'static.xx.fbcdn.net' && image.alt;
            } catch {
              return false;
            }
          });
          const badImages = brokenImages.filter((image) => !externalImageFallbacks.includes(image));
          const emptyHrefs = [...document.querySelectorAll('a')].filter(visible)
            .filter((anchor) => {
              const href = (anchor.getAttribute('href') || '').trim();
              return !href || href === '#' || /^javascript:/i.test(href);
            })
            .map(selectorFor);
          const content = document.querySelector('.article-content');
          const contentStyle = content ? getComputedStyle(content) : null;
          const grid = document.querySelector('.tnews-grid');
          const filter = document.querySelector('.tnews-filter-panel');

          return {
            root: Boolean(root),
            overflow: document.documentElement.scrollWidth > innerWidth || document.body.scrollWidth > innerWidth,
            h1Count: h1s.length,
            h1Size: h1s[0] ? Number.parseFloat(getComputedStyle(h1s[0]).fontSize) : null,
            h1NestedColorCount: h1s[0] ? [...h1s[0].querySelectorAll('*')].filter((element) => getComputedStyle(element).color !== getComputedStyle(h1s[0]).color).length : null,
            smallText,
            undersized,
            unexpectedColors,
            badImages,
            externalImageFallbacks,
            emptyHrefs,
            cardCount: document.querySelectorAll('.tnews-card').length,
            gridColumns: grid ? getComputedStyle(grid).gridTemplateColumns.split(' ').filter(Boolean).length : null,
            filterWidth: filter ? Number(filter.getBoundingClientRect().width.toFixed(1)) : null,
            articleContent: content ? {
              width: Number(content.getBoundingClientRect().width.toFixed(1)),
              fontSize: Number.parseFloat(contentStyle.fontSize),
              lineHeight: Number.parseFloat(contentStyle.lineHeight)
            } : null
          };
        }, { kind: target.kind, allowedColors: [...canonicalColors] });

        const runFailures = [];
        if (!response || response.status() !== 200) runFailures.push(`HTTP ${response ? response.status() : 'none'}`);
        if (!metrics.root) runFailures.push('missing page root');
        if (metrics.overflow) runFailures.push('horizontal overflow');
        if (consoleErrors.length) runFailures.push(`console errors: ${consoleErrors.length}`);
        if (pageErrors.length) runFailures.push(`page errors: ${pageErrors.length}`);
        if (metrics.h1Count !== 1 || metrics.h1Size !== 48 || metrics.h1NestedColorCount !== 0) runFailures.push('H1 contract');
        if (metrics.smallText.length) runFailures.push(`text under 15px: ${metrics.smallText.length}`);
        if (metrics.undersized.length) runFailures.push(`controls under 44px: ${metrics.undersized.length}`);
        if (metrics.unexpectedColors.length) runFailures.push(`unexpected colors: ${metrics.unexpectedColors.length}`);
        if (metrics.badImages.length) runFailures.push(`broken images: ${metrics.badImages.length}`);
        if (metrics.emptyHrefs.length) runFailures.push(`empty hrefs: ${metrics.emptyHrefs.length}`);

        if (target.kind === 'news') {
          if (metrics.cardCount < 1) runFailures.push('news cards missing');
          const expectedColumns = viewport.width === 1440 ? 3 : 2;
          if (metrics.gridColumns !== expectedColumns) runFailures.push(`news grid columns ${metrics.gridColumns}, expected ${expectedColumns}`);
          const collapse = page.locator('[data-filter-collapse]');
          await collapse.click();
          const collapsed = await page.evaluate(() => ({
            shell: document.querySelector('[data-news-shell]')?.classList.contains('is-filter-collapsed'),
            expanded: document.querySelector('[data-filter-collapse]')?.getAttribute('aria-expanded'),
            width: Number(document.querySelector('.tnews-filter-panel')?.getBoundingClientRect().width.toFixed(1))
          }));
          if (!collapsed.shell || collapsed.expanded !== 'false' || collapsed.width > 64) runFailures.push(`filter collapse contract: ${JSON.stringify(collapsed)}`);
          await collapse.click();
          const expanded = await page.evaluate(() => ({
            shell: document.querySelector('[data-news-shell]')?.classList.contains('is-filter-collapsed'),
            expanded: document.querySelector('[data-filter-collapse]')?.getAttribute('aria-expanded')
          }));
          if (expanded.shell || expanded.expanded !== 'true') runFailures.push(`filter expand contract: ${JSON.stringify(expanded)}`);
        } else if (!metrics.articleContent || metrics.articleContent.width > 780 || metrics.articleContent.fontSize !== 20 || metrics.articleContent.lineHeight < 36) {
          runFailures.push(`article reading measure: ${JSON.stringify(metrics.articleContent)}`);
        }

        if (target.kind === 'news') {
          await page.screenshot({
            path: path.join(process.env.ARTIFACT_DIR, `news-${viewport.width}.png`),
            fullPage: true
          });
        } else if (target.index === 0) {
          await page.evaluate(() => scrollTo(0, 0));
          await page.screenshot({ path: path.join(process.env.ARTIFACT_DIR, `single-${viewport.width}-top.png`) });
          const firstContentImage = page.locator('.article-content img').first();
          if (await firstContentImage.count()) {
            await firstContentImage.scrollIntoViewIfNeeded();
            await firstContentImage.evaluate((image) => image.decode());
            await page.screenshot({ path: path.join(process.env.ARTIFACT_DIR, `single-${viewport.width}-body.png`) });
          }
        }

        const result = { target, viewport, metrics, consoleErrors, pageErrors, failures: runFailures };
        results.push(result);
        if (runFailures.length) failures.push(result);
        await page.close();
      }
      await context.close();
    }

    const noJsContext = await browser.newContext({
      viewport: { width: 1440, height: 900 },
      ignoreHTTPSErrors: true,
      javaScriptEnabled: false
    });
    await noJsContext.route('https://fwdignity.com/wp-content/uploads/**', async (route) => {
      const localUrl = route.request().url().replace('https://fwdignity.com', baseUrl);
      const localResponse = await route.fetch({ url: localUrl });
      await route.fulfill({ response: localResponse });
    });
    const noJsPage = await noJsContext.newPage();
    const noJsResponse = await noJsPage.goto(`${baseUrl}/thenews/`, { waitUntil: 'domcontentloaded' });
    const noJsState = await noJsPage.evaluate(() => ({
      formVisible: document.querySelector('.tnews-filter-form')?.getBoundingClientRect().height > 0,
      cards: document.querySelectorAll('.tnews-card').length
    }));
    if (!noJsResponse || noJsResponse.status() !== 200 || !noJsState.formVisible || noJsState.cards < 1) {
      failures.push({ target: { kind: 'news-no-js', url: `${baseUrl}/thenews/` }, viewport: { width: 1440, height: 900 }, noJsState, failures: ['no-JS fallback'] });
    }
    await noJsContext.close();
  } finally {
    await browser.close();
  }

  const summary = {
    generatedAt: new Date().toISOString(),
    totalRuns: results.length,
    failedRuns: failures.length,
    viewports,
    newsRuns: results.filter((result) => result.target.kind === 'news').length,
    singleRuns: results.filter((result) => result.target.kind === 'single').length,
    externalImageFallbacks: results.reduce((total, result) => total + result.metrics.externalImageFallbacks.length, 0),
    failures,
    results
  };
  fs.writeFileSync(path.join(process.env.ARTIFACT_DIR, 'summary.json'), JSON.stringify(summary, null, 2));
  if (failures.length) {
    console.error(JSON.stringify({ totalRuns: summary.totalRuns, failedRuns: summary.failedRuns, failures }, null, 2));
    process.exit(1);
  }
  console.log(`Playwright PASS: ${summary.totalRuns}/${summary.totalRuns} runs (news ${summary.newsRuns}, single ${summary.singleRuns})`);
})().catch((error) => {
  console.error(error);
  process.exit(2);
});
NODE
    then
        pass '1440/1025 Playwright news and all published singles'
    else
        fail '1440/1025 Playwright news and all published singles'
    fi
}

case "$MODE" in
    static)
        run_static_checks
        ;;
    browser)
        run_browser_checks
        ;;
    all)
        run_static_checks
        if [ "$failures" -eq 0 ]; then
            run_browser_checks
        else
            printf 'SKIP  browser checks until static checks pass\n'
        fi
        ;;
    *)
        printf 'Usage: %s [static|browser|all]\n' "$0" >&2
        exit 2
        ;;
esac

if [ "$failures" -ne 0 ]; then
    printf 'RESULT FAIL (%d failed / %d checks)\n' "$failures" "$checks" >&2
    exit 1
fi

printf 'RESULT PASS (%d checks)\n' "$checks"
