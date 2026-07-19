#!/usr/bin/env bash
set -euo pipefail

BASE_URL="${BASE_URL:-https://fwdignity-local.local}"
HIDDEN_PATH='/dignity-farming-initiative/'
PUBLIC_SOURCES=(
    '/'
    '/thenews/'
    '/field-learning/'
    '/field-notes/'
    '/farmer-study/'
    '/podcast/'
    '/about/'
    '/about/researchteam/'
    '/people-who-farm/'
    '/research-results/'
    '/research-results/research-publications/'
    '/research-results/related-resources/'
)

fail() {
    printf '%s\n' "$1" >&2
    exit 1
}

hidden_status="$(curl --silent --show-error --insecure --location --output /dev/null --write-out '%{http_code}' "$BASE_URL$HIDDEN_PATH")"
[ "$hidden_status" = '404' ] || fail "倡議頁應維持隱藏並回應404，實際為 $hidden_status"

linked_sources=()
for source in "${PUBLIC_SOURCES[@]}"; do
    html="$(curl --silent --show-error --fail --insecure --location "$BASE_URL$source")"
    if grep -Fq "$HIDDEN_PATH" <<<"$html"; then
        linked_sources+=( "$source" )
    fi
done

[ "${#linked_sources[@]}" -eq 0 ] || fail "公開頁仍連到已隱藏倡議頁：${linked_sources[*]}"

printf '隱藏倡議頁驗收通過：維持404，所有公開入口均未連結\n'
