#!/usr/bin/env bash
set -u

repo_dir="$(cd "$(dirname "$0")/.." && pwd)"
css="$repo_dir/themes/fwdignity/assets/css/site-v3-layout-final.css"
failures=0

require_fixed() {
  local needle="$1"
  local label="$2"
  if grep -Fq -- "$needle" "$css"; then
    printf 'PASS: %s\n' "$label"
  else
    printf 'FAIL: %s\n' "$label" >&2
    failures=$((failures + 1))
  fi
}

require_fixed '一般文章特色圖：依原圖比例自然縮放，不建立固定灰色畫布。' 'adaptive single-image rule is documented'
require_fixed '.article-entry:not(.category-podcast):has(.article-featured-image) .article-featured-image {' 'general article images are isolated from Podcast'
require_fixed 'max-height: 480px !important;' 'general article image keeps the approved height cap'
require_fixed '.article-entry.category-podcast:has(.article-featured-image) .article-featured-image {' 'Podcast keeps its dedicated image role'
require_fixed 'aspect-ratio: 1 / 1 !important;' 'Podcast keeps the approved square cover'

if (( failures > 0 )); then
  printf '\n%d single-image contract check(s) failed.\n' "$failures" >&2
  exit 1
fi

printf '\nAll single-image contract checks passed.\n'
