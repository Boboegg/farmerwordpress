#!/usr/bin/env bash
set -euo pipefail

PWCLI="${PWCLI:-$HOME/.codex/skills/playwright/scripts/playwright_cli.sh}"
SESSION="audience-visible-test-$$"
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
    https://fwdignity-local.local/about/ >/dev/null

"$PWCLI" --session "$SESSION" run-code "async (page) => {
  const paths = [
    '/about/',
    '/about/researchteam/',
    '/research-results/',
    '/research-results/research-publications/',
    '/research-results/related-resources/',
    '/dignity-farming-initiative/'
  ];
  const failures = [];

  for (const path of paths) {
    await page.goto('https://fwdignity-local.local' + path, { waitUntil: 'networkidle' });
    const hiddenCount = await page.locator('.reveal').evaluateAll((nodes) => nodes.filter((node) => {
      const style = getComputedStyle(node);
      return Number.parseFloat(style.opacity) < 0.1 || style.visibility === 'hidden';
    }).length);
    if (hiddenCount > 0) failures.push({ path, hiddenCount });
  }

  if (failures.length) {
    throw new Error('Content hidden before scrolling: ' + JSON.stringify(failures));
  }
  return { checked: paths.length, hiddenCount: 0 };
}"
