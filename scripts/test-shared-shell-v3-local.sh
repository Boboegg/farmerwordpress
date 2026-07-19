#!/usr/bin/env bash
set -euo pipefail

repo_dir="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
layout="$repo_dir/themes/fwdignity/assets/css/layout.css"

fail() {
    printf 'FAIL: %s\n' "$1" >&2
    exit 1
}

assert_block_contains() {
    local selector="$1"
    local declaration="$2"

    awk -v selector="$selector" -v declaration="$declaration" '
        BEGIN { RS = "}"; found = 0 }
        index($0, selector) && index($0, declaration) { found = 1 }
        END { exit found ? 0 : 1 }
    ' "$layout" || fail "$selector must contain $declaration"
}

assert_block_contains '.fw-nav-submenu-toggle' 'width: 44px;'
assert_block_contains '.fw-nav-submenu-toggle' 'min-height: 44px;'
assert_block_contains '.fw-footer-contact a' 'min-height: 44px;'
assert_block_contains '.fw-footer-contact a' 'display: inline-flex;'
assert_block_contains '.fw-footer-social a' 'width: 44px; height: 44px;'

for selector in \
    '.fw-footer-search > div' \
    '.fw-footer-unit-label' \
    '.fw-footer-contact a' \
    '.fw-footer-social a' \
    '.fw-footer-tags a' \
    '.fw-footer-bottom'
do
    block="$(awk -v selector="$selector" 'BEGIN { RS = "}" } index($0, selector) { print; exit }' "$layout")"
    [ -n "$block" ] || fail "missing footer selector: $selector"
    if grep -Eiq 'rgba?\(|#[0-9a-f]{3,8}\b' <<<"$block"; then
        fail "$selector still contains a literal color outside the Clear Field token system"
    fi
done

printf 'shared shell v3 checks passed\n'
