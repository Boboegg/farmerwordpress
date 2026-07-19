#!/usr/bin/env bash
set -euo pipefail

PWCLI="${PWCLI:-$HOME/.codex/skills/playwright/scripts/playwright_cli.sh}"
SESSION="public-full-width-test-$$"
CONFIG="$(mktemp)"
OUTPUT_DIR="$(mktemp -d "${TMPDIR:-/tmp}/fwdignity-pw.XXXXXX")"

cleanup() {
    "$PWCLI" --session "$SESSION" close >/dev/null 2>&1 || true
    rm -f "$CONFIG"
    rm -rf -- "$OUTPUT_DIR"
}
trap cleanup EXIT

printf '{"outputDir":"%s","browser":{"contextOptions":{"ignoreHTTPSErrors":true}}}\n' "$OUTPUT_DIR" > "$CONFIG"
"$PWCLI" --session "$SESSION" --config "$CONFIG" open https://fwdignity-local.local/about/ >/dev/null

OUTPUT="$("$PWCLI" --session "$SESSION" run-code "async (page) => {
  const paths = [
    '/about/',
    '/about/researchteam/',
    '/research-results/',
    '/research-results/research-publications/',
    '/research-results/related-resources/'
  ];
  const failures = [];

  for (const width of [1440, 800]) {
    await page.setViewportSize({ width, height: 1000 });
    for (const path of paths) {
      await page.goto('https://fwdignity-local.local' + path, { waitUntil: 'networkidle' });
      const state = await page.evaluate(() => {
        const layout = document.querySelector('.fw-page-layout');
        const main = document.querySelector('#main');
        return {
          asideCount: document.querySelectorAll('.fw-aside').length,
          fullClass: layout?.classList.contains('fw-page-layout--full') || false,
          overflow: document.documentElement.scrollWidth > document.documentElement.clientWidth + 1,
          mainVisible: !!main && main.getBoundingClientRect().height > 0
        };
      });
      if (state.asideCount || !state.fullClass || state.overflow || !state.mainVisible) {
        failures.push({ width, path, ...state });
      }
    }
  }

  if (failures.length) throw new Error(JSON.stringify(failures));
  return { checked: paths.length * 2, paths: paths.length };
}")"

printf '%s\n' "$OUTPUT"
if printf '%s\n' "$OUTPUT" | grep -q '^### Error'; then
    exit 1
fi
