#!/usr/bin/env bash
set -euo pipefail

PWCLI="${PWCLI:-$HOME/.codex/skills/playwright/scripts/playwright_cli.sh}"
SESSION="shell-accessibility-test-$$"
CONFIG="$(mktemp)"
OUTPUT_DIR="$(mktemp -d "${TMPDIR:-/tmp}/fwdignity-pw.XXXXXX")"

cleanup() {
    "$PWCLI" --session "$SESSION" close >/dev/null 2>&1 || true
    rm -f "$CONFIG"
    rm -rf -- "$OUTPUT_DIR"
}
trap cleanup EXIT

printf '{"outputDir":"%s","browser":{"contextOptions":{"ignoreHTTPSErrors":true}}}\n' "$OUTPUT_DIR" > "$CONFIG"

"$PWCLI" --session "$SESSION" --config "$CONFIG" open \
    https://fwdignity-local.local/ >/dev/null

"$PWCLI" --session "$SESSION" run-code "async (page) => {
  const paths = ['/', '/thenews/', '/field-learning/', '/field-notes/', '/farmer-study/', '/podcast/'];
  const failures = [];

  for (const path of paths) {
    await page.goto('https://fwdignity-local.local' + path, { waitUntil: 'networkidle' });
    const href = await page.locator('.skip-link').getAttribute('href');
    const targetCount = href ? await page.locator(href).count() : 0;
    if (href !== '#main' || targetCount !== 1) failures.push({ path, href, targetCount });
  }

  await page.setViewportSize({ width: 800, height: 1000 });
  await page.goto('https://fwdignity-local.local/', { waitUntil: 'networkidle' });
  await page.locator('.fw-hamburger').click();
  const focusedAfterOpen = await page.evaluate(() => document.activeElement?.closest('.fw-nav-menu') !== null);
  await page.keyboard.press('Escape');
  const focusedAfterEscape = await page.evaluate(() => document.activeElement?.classList.contains('fw-hamburger') || false);

  const context = await page.context().browser().newContext({ javaScriptEnabled: false, ignoreHTTPSErrors: true, viewport: { width: 800, height: 1000 } });
  const nojs = await context.newPage();
  await nojs.goto('https://fwdignity-local.local/', { waitUntil: 'networkidle' });
  const nojsMenuVisible = await nojs.locator('.fw-nav-menu').isVisible();
  const nojsLinks = await nojs.locator('.fw-nav-menu a:visible').count();
  await context.close();

  if (failures.length || !focusedAfterOpen || !focusedAfterEscape || !nojsMenuVisible || nojsLinks < 6) {
    throw new Error(JSON.stringify({ failures, focusedAfterOpen, focusedAfterEscape, nojsMenuVisible, nojsLinks }));
  }
  return { checkedPaths: paths.length, focusedAfterOpen, focusedAfterEscape, nojsMenuVisible, nojsLinks };
}"
