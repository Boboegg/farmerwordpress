#!/usr/bin/env bash
set -euo pipefail

PWCLI="${PWCLI:-$HOME/.codex/skills/playwright/scripts/playwright_cli.sh}"
SESSION="home-draft-isolation-test-$$"
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
  const tasks = page.locator('.fw-home-v07-guide-task');
  const taskLabels = await tasks.locator('strong').allTextContents();
  const taskLinks = await tasks.locator('a').count();
  const guideLinks = await page.locator('.fw-home-v07-guide-link[href$=\"/farming-guide/\"]').count();
  const leakedDraftSummary = await page.locator('body').innerText().then((text) => text.includes('從裝備到制度，降低田間每一個風險因子'));
  const expectedTasks = ['今天下田前', '農機與工具', '農藥與防護', '身體疲勞與心理', '受傷或發生意外', '保險權益與補助'];

  if (JSON.stringify(taskLabels) !== JSON.stringify(expectedTasks) || taskLinks !== 0 || guideLinks !== 1 || leakedDraftSummary) {
    throw new Error(JSON.stringify({ taskLabels, taskLinks, guideLinks, leakedDraftSummary }));
  }
  return { taskLabels, taskLinks, guideLinks, leakedDraftSummary };
}"
