#!/usr/bin/env bash
set -euo pipefail

ROOT_DIR="$(cd "$(dirname "$0")/.." && pwd)"
IMPORT_DIR="$ROOT_DIR/imports"

if [[ ! -d "$IMPORT_DIR" ]]; then
  echo "找不到 $IMPORT_DIR，請先建立 imports 資料夾並放入完整 HTML 檔。"
  exit 1
fi

copy_if_exists() {
  local src="$1"
  local dst="$2"

  if [[ -f "$IMPORT_DIR/$src" ]]; then
    cp "$IMPORT_DIR/$src" "$ROOT_DIR/$dst"
    echo "已匯入: $src -> $dst"
  else
    echo "略過（未找到）: $src"
  fi
}

copy_if_exists "page-研究成果.html" "pages/research-result/研究成果"
copy_if_exists "page-研究成果-研究報告.html" "pages/research-result/page-研究成果-研究報告.html"
copy_if_exists "page-尊嚴農業倡議.html" "pages/dignity-farming-initiative/尊嚴農業倡議"
copy_if_exists "page-農學堂.html" "pages/farmer-study/農學堂"
copy_if_exists "prevention-page-complete.html" "pages/occupational-safety/職業安全"

copy_if_exists "subpage-crop-insurance.html" "pages/economic-insurance/crop-insurance/農業保險"
copy_if_exists "subpage-greenhouse.html" "pages/occupational-safety/greenhouse-zone/溫室專區"
copy_if_exists "subpage-machine.html" "pages/occupational-safety/labor-saving-machinery/省工農機具"
copy_if_exists "subpage-ppe.html" "pages/occupational-safety/personal-protective-equipment/個人防護具"
copy_if_exists "subpage-subsidy.html" "pages/economic-insurance/agricultural-subsidy-resources/農業補助資源"
copy_if_exists "subpage-occupational-insurance.html" "pages/economic-insurance/occupational-accident-insurance/職業災害保險"

copy_if_exists "subpage-heat.html" "pages/healthgood/subpage-heat/熱傷害"
copy_if_exists "subpage-pesticide.html" "pages/healthgood/subpage-pesticide/農藥安全"
copy_if_exists "subpage-msd.html" "pages/healthgood/subpage-msd/肌肉骨骼"
copy_if_exists "subpage-mental.html" "pages/healthgood/subpage-mental/心理健康"

echo "匯入流程結束。"
