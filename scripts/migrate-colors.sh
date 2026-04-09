#!/usr/bin/env bash

# =========================================================
# 批次色碼遷移腳本
# 用途：將 pages/ 下既有 HTML 的舊色碼替換為 design token 變數
# 相容：macOS BSD sed
# =========================================================

set -u

ROOT_DIR="$(cd "$(dirname "$0")/.." && pwd)"
PAGES_DIR="$ROOT_DIR/pages"
DRY_RUN=0

if [ "${1:-}" = "--dry-run" ]; then
  DRY_RUN=1
fi

PATTERNS=(
  '#[cC]62828|#[bB]71[cC]1[cC]|#[dD]32[fF]2[fF]'
  '#1565[cC]0'
  '#2[eE]7[dD]32|#5[cC]8607'
  '#6[bB]7280|#9[cC]a3[aA][fF]'
  '#1[fF]2937|#374151'
  '#[fF]0[fF]0[fF]0|#[fF]5[fF]5[fF]5|#[fF]8[fF]a[fA][cC]|#[fF]9[fF]a[fA][bB]'
  '#[eE]2[eE]8[fF]0|#[eE][eE][fF]2[fF]7'
  '#2[cC]5[eE]2[eE]'
  '#[fF]59[eE]0[bB]'
)

REPLACEMENTS=(
  'var(--color-warn)'
  'var(--color-info)'
  'var(--color-brand)'
  'var(--color-muted)'
  'var(--color-text)'
  'var(--color-bg-elevated)'
  'var(--color-line)'
  'var(--color-brand-deep)'
  'var(--color-accent)'
)

count_matches() {
  local pattern="$1"
  local file="$2"
  rg -o -i -e "$pattern" "$file" 2>/dev/null | wc -l | tr -d ' '
}

collect_changes() {
  local file="$1"
  local total=0
  local i count

  for ((i = 0; i < ${#PATTERNS[@]}; i++)); do
    count="$(count_matches "${PATTERNS[$i]}" "$file")"
    total=$((total + count))
  done

  printf '%s' "$total"
}

apply_changes() {
  local file="$1"
  local sed_args=()
  local i

  for ((i = 0; i < ${#PATTERNS[@]}; i++)); do
    sed_args+=(-e "s/${PATTERNS[$i]}/${REPLACEMENTS[$i]}/g")
  done

  rm -f "${file}.bak"
  cp "$file" "${file}.bak"
  sed -E -i '' "${sed_args[@]}" "$file"
}

FILES=()
CHANGED_FILES=()
UNTOUCHED_FILES=()
TOTAL_FILES=0
TOTAL_CHANGED=0
TOTAL_REPLACEMENTS=0

while IFS= read -r file; do
  FILES+=("$file")
done < <(
  find "$PAGES_DIR" -type f \
    ! -name '*.bak' ! -name '*.md' ! -name '*.json' \
    ! -name '*.png' ! -name '*.jpg' ! -name '*.jpeg' \
    ! -name '*.gif' ! -name '*.svg' ! -name '*.webp' \
    ! -name '*.gitkeep' \
    -exec grep -lE '<(div|section|style|wp:html|!--)' {} \; \
    | sort
)

TOTAL_FILES=${#FILES[@]}

if [ "$TOTAL_FILES" -eq 0 ]; then
  echo "找不到任何 HTML 檔案：$PAGES_DIR"
  exit 1
fi

echo "掃描目錄：$PAGES_DIR"
if [ "$DRY_RUN" -eq 1 ]; then
  echo "模式：dry-run（不修改檔案）"
else
  echo "模式：apply（實際修改檔案，先建立 .bak）"
fi
echo

for file in "${FILES[@]}"; do
  changes="$(collect_changes "$file")"

  if [ "$changes" -gt 0 ]; then
    CHANGED_FILES+=("$file")
    TOTAL_CHANGED=$((TOTAL_CHANGED + 1))
    TOTAL_REPLACEMENTS=$((TOTAL_REPLACEMENTS + changes))
    printf '[會處理] %s (%s 次)\n' "$file" "$changes"

    if [ "$DRY_RUN" -eq 0 ]; then
      apply_changes "$file"
    fi
  else
    UNTOUCHED_FILES+=("$file")
  fi
done

echo
echo "========== Summary =========="
printf '總檔案數：%s\n' "$TOTAL_FILES"
printf '有變動檔案：%s\n' "$TOTAL_CHANGED"
printf '總替換次數：%s\n' "$TOTAL_REPLACEMENTS"

echo "未動到檔案："
if [ "${#UNTOUCHED_FILES[@]}" -eq 0 ]; then
  echo "（無）"
else
  for file in "${UNTOUCHED_FILES[@]}"; do
    printf '%s\n' "$file"
  done
fi
