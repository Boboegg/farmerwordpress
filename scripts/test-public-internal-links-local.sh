#!/usr/bin/env bash
set -euo pipefail

PWCLI="${PWCLI:-$HOME/.codex/skills/playwright/scripts/playwright_cli.sh}"
SESSION="public-link-test-$$"
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

result="$("$PWCLI" --session "$SESSION" run-code "async (page) => {
  const sources = [
    '/', '/thenews/', '/field-learning/', '/field-notes/', '/farmer-study/', '/podcast/',
    '/about/', '/about/researchteam/', '/people-who-farm/', '/research-results/',
    '/research-results/research-publications/', '/research-results/related-resources/'
  ];
  const links = {};

  for (const source of sources) {
    await page.goto('https://fwdignity-local.local' + source, { waitUntil: 'networkidle' });
    const hrefs = await page.locator('a[href]').evaluateAll((nodes) => nodes.map((node) => node.href.split('#')[0].split('?')[0]));
    for (const href of hrefs) {
      if (!href.startsWith('https://fwdignity-local.local/') || href.includes('/wp-') || href.includes('/feed/')) continue;
      links[href] = links[href] || [];
      links[href].push(source);
    }
  }

  const failures = [];
  for (const url of Object.keys(links)) {
    const response = await page.request.get(url, { maxRedirects: 0, failOnStatusCode: false });
    if (response.status() >= 300) {
      failures.push({ url, status: response.status(), sources: [...new Set(links[url])] });
    }
  }

  const hiddenUrl = 'https://fwdignity-local.local/dignity-farming-initiative/';
  const hiddenResponse = await page.request.get(hiddenUrl, { failOnStatusCode: false });
  if (hiddenResponse.status() !== 404) {
    failures.push({ url: hiddenUrl, status: hiddenResponse.status(), expected: 404 });
  }

  if (failures.length) throw new Error(JSON.stringify(failures));
  return { sourcePages: sources.length, checkedLinks: Object.keys(links).length, failures: 0 };
}")"

printf '%s\n' "$result"
if grep -Fq '### Error' <<<"$result"; then
    exit 1
fi
