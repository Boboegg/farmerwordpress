#!/usr/bin/env bash
set -euo pipefail

script_dir="$(cd "$(dirname "$0")" && pwd)"
site_url="${1:-https://fwdignity-local.local/}"

bash "$script_dir/test-site-v4-architecture-local.sh" >/dev/null

html="$(curl --silent --show-error --fail --insecure --location "$site_url")"

if grep -Fq 'fw-ticker-row' <<<"$html"; then
    printf 'legacy ticker is still rendered\n' >&2
    exit 1
fi

if grep -Fq '/dignity-farming-initiative/' <<<"$html"; then
    printf 'hidden advocacy Page is still linked from the homepage\n' >&2
    exit 1
fi

if grep -Fq 'Design System MASTER FINAL' <<<"$html"; then
    printf 'legacy footer version text is still rendered\n' >&2
    exit 1
fi

if grep -Eq 'href="[^"]*\?page_id=(1328|1329|1330)"' <<<"$html"; then
    printf 'draft topic Page is exposed from the homepage\n' >&2
    exit 1
fi

# 2026-07-16 W1-3：hero 改用壓縮衍生檔（-1600w/-2400w srcset），
# 以基底檔名比對，同時相容原檔與衍生檔。
grep -Fq 'homepage-field-equipment-20260702' <<<"$html" || {
    printf 'approved homepage field image is missing\n' >&2
    exit 1
}

printf 'current LocalWP shell smoke test passed: %s\n' "$site_url"
