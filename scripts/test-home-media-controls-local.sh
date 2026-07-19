#!/usr/bin/env bash
set -euo pipefail

PWCLI="${PWCLI:-$HOME/.codex/skills/playwright/scripts/playwright_cli.sh}"
SESSION="home-media-controls-test-$$"
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
  await page.setViewportSize({ width: 800, height: 1000 });
  await page.reload({ waitUntil: 'networkidle' });
  const section = page.locator('#home-media');
  await section.scrollIntoViewIfNeeded();
  const toggle = page.locator('[data-home-media-toggle]');
  const controlSizes = await page.locator('.fw-home-v06-compact-controls button, .fw-home-v06-media-controls button').evaluateAll((nodes) => nodes.map((node) => {
    const box = node.getBoundingClientRect();
    return { width: Math.round(box.width), height: Math.round(box.height), label: node.getAttribute('aria-label') };
  }));

  if (await toggle.count() !== 1 || controlSizes.some((item) => item.width < 44 || item.height < 44)) {
    throw new Error(JSON.stringify({ toggleCount: await toggle.count(), controlSizes }));
  }

  await toggle.click();
  const viewport = page.locator('[data-home-media-viewport]');
  const before = await viewport.evaluate((node) => node.scrollLeft);
  await page.waitForTimeout(8500);
  const after = await viewport.evaluate((node) => node.scrollLeft);
  const pressed = await toggle.getAttribute('aria-pressed');
  if (Math.abs(after - before) > 2 || pressed !== 'true') {
    throw new Error(JSON.stringify({ before, after, pressed }));
  }
  return { controls: controlSizes.length, pausedScroll: after, pressed };
}"
