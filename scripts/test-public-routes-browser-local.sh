#!/usr/bin/env bash
set -euo pipefail

PWCLI="${PWCLI:-$HOME/.codex/skills/playwright/scripts/playwright_cli.sh}"
SESSION="public-routes-browser-test-$$"
CONFIG="$(mktemp)"
OUTPUT_DIR="$(mktemp -d "${TMPDIR:-/tmp}/fwdignity-pw.XXXXXX")"

cleanup() {
    "$PWCLI" --session "$SESSION" close >/dev/null 2>&1 || true
    rm -f "$CONFIG"
    rm -rf -- "$OUTPUT_DIR"
}
trap cleanup EXIT

printf '{"outputDir":"%s","browser":{"contextOptions":{"ignoreHTTPSErrors":true}}}\n' "$OUTPUT_DIR" > "$CONFIG"
"$PWCLI" --session "$SESSION" --config "$CONFIG" open https://fwdignity-local.local/ >/dev/null

OUTPUT="$("$PWCLI" --session "$SESSION" run-code "async (page) => {
  const paths = [
    '/',
    '/thenews/',
    '/field-learning/',
    '/field-notes/',
    '/farmer-study/',
    '/podcast/',
    '/research-results/',
    '/research-results/research-publications/',
    '/research-results/related-resources/',
    '/about/',
    '/about/researchteam/',
    '/people-who-farm/',
    '/people-who-farm/body-and-farm-work/',
    '/people-who-farm/changing-farm-work/',
    '/people-who-farm/rural-support/',
    '/people-who-farm/risk-and-responsibility/',
    '/people-who-farm/knowledge-in-the-field/',
    '/farming-guide/'
  ];
  const failures = [];
  const pageErrors = [];
  page.on('pageerror', (error) => pageErrors.push(String(error)));
  page.on('console', (message) => {
    if (message.type() === 'error') pageErrors.push(message.text());
  });

  for (const width of [1440, 800]) {
    await page.setViewportSize({ width, height: 1000 });
    for (const path of paths) {
      const response = await page.goto('https://fwdignity-local.local' + path, { waitUntil: 'networkidle' });
      const state = await page.evaluate(() => {
        const main = document.querySelector('#main');
        const brokenImages = [...document.images]
          .filter((image) => {
            const box = image.getBoundingClientRect();
            return box.width > 0 && box.height > 0 && image.complete && image.naturalWidth === 0;
          })
          .map((image) => image.currentSrc || image.src);
        const hiddenReveal = [...document.querySelectorAll('.reveal')]
          .filter((node) => {
            const style = getComputedStyle(node);
            const box = node.getBoundingClientRect();
            return style.opacity === '0' || style.visibility === 'hidden' || box.height === 0;
          }).length;
        return {
          statusMain: document.querySelectorAll('#main').length,
          mainVisible: !!main && main.getBoundingClientRect().height > 0,
          overflow: document.documentElement.scrollWidth > document.documentElement.clientWidth + 1,
          skipHref: document.querySelector('.skip-link')?.getAttribute('href') || '',
          brokenImages,
          hiddenReveal
        };
      });
      const status = response?.status() || 0;
      if (status !== 200 || state.statusMain !== 1 || !state.mainVisible || state.overflow || state.skipHref !== '#main' || state.brokenImages.length || state.hiddenReveal) {
        failures.push({ width, path, status, ...state });
      }
    }
  }

  const hiddenResponse = await page.request.get('https://fwdignity-local.local/dignity-farming-initiative/', { failOnStatusCode: false });
  if (hiddenResponse.status() !== 404) failures.push({ path: '/dignity-farming-initiative/', status: hiddenResponse.status(), expected: 404 });

  const localErrors = pageErrors.filter((message) => !message.includes('ERR_BLOCKED_BY_CLIENT'));
  if (failures.length || localErrors.length) throw new Error(JSON.stringify({ failures, localErrors }));
  return { paths: paths.length, checks: paths.length * 2, consoleErrors: localErrors.length };
}")"

printf '%s\n' "$OUTPUT"
if printf '%s\n' "$OUTPUT" | grep -q '^### Error'; then
    exit 1
fi
