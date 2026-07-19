#!/usr/bin/env bash
set -euo pipefail

PWCLI="${PWCLI:-$HOME/.codex/skills/playwright/scripts/playwright_cli.sh}"
SESSION="filter-control-accessibility-test-$$"
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
    https://fwdignity-local.local/thenews/ >/dev/null

OUTPUT="$("$PWCLI" --session "$SESSION" run-code "async (page) => {
  await page.setViewportSize({ width: 1440, height: 1000 });
  const results = [];

  const inspect = async (path, openSelector, targetSelector, inputSelector, proxySelector) => {
    await page.goto('https://fwdignity-local.local' + path, { waitUntil: 'networkidle' });
    if (openSelector) {
      const opener = page.locator(openSelector).first();
      if (await opener.count()) await opener.click();
    }

    const sizes = await page.locator(targetSelector).evaluateAll((nodes) => nodes
      .filter((node) => {
        const style = getComputedStyle(node);
        const box = node.getBoundingClientRect();
        return style.display !== 'none' && style.visibility !== 'hidden' && box.width > 0 && box.height > 0;
      })
      .map((node) => {
        const box = node.getBoundingClientRect();
        return {
          tag: node.tagName.toLowerCase(),
          className: node.className,
          text: (node.textContent || '').trim().slice(0, 30),
          width: Math.round(box.width),
          height: Math.round(box.height)
        };
      }));

    const input = page.locator(inputSelector).first();
    let focusVisible = true;
    if (await input.count()) {
      await input.focus();
      focusVisible = await page.locator(proxySelector).first().evaluate((node) => {
        const style = getComputedStyle(node);
        return style.outlineStyle !== 'none' || style.boxShadow !== 'none';
      });
    }

    const undersized = sizes.filter((item) => item.width < 44 || item.height < 44);
    results.push({ path, checked: sizes.length, undersized, focusVisible });
  };

  await inspect(
    '/thenews/',
    null,
    '.tnews-filter-collapse, .tnews-facet-option, .tnews-apply, .tnews-clear, .tnews-pagination .tnews-page-link',
    '.tnews-facet-option input',
    '.tnews-facet-option input:focus + .tnews-facet-check'
  );
  await inspect(
    '/field-notes/',
    '.field-notes-filter-toggle',
    '.field-notes-filter-close, .field-notes-filter-option, .field-notes-filter-actions button, .field-notes-pagination .page-numbers',
    '.field-notes-filter-option input',
    '.field-notes-filter-option input:focus + .field-notes-checkbox'
  );
  await inspect(
    '/podcast/#mh-archive',
    '[data-media-filter-toggle]',
    '.mh-filter-drawer-head button, .mh-filter-drawer fieldset label, .mh-filter-actions .mh-action-button, .mh-pagination .page-numbers',
    '.mh-filter-drawer fieldset input',
    '.mh-filter-drawer fieldset label:has(input:focus)'
  );

  const failures = results.filter((result) => result.undersized.length || !result.focusVisible);
  if (failures.length) throw new Error(JSON.stringify(failures));
  return results;
}")"

printf '%s\n' "$OUTPUT"
if printf '%s\n' "$OUTPUT" | grep -q '^### Error'; then
    exit 1
fi
