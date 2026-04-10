#!/usr/bin/env bash
# 稽核 pages 與 page-map.json 的覆蓋狀態
# 用法：bash scripts/audit-page-coverage.sh [--json]

set -euo pipefail

ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
PAGE_MAP_FILE="$ROOT_DIR/scripts/page-map.json"
OUTPUT_MODE="text"

if [[ "${1:-}" == "--json" ]]; then
  OUTPUT_MODE="json"
fi

python3 - "$ROOT_DIR" "$PAGE_MAP_FILE" "$OUTPUT_MODE" <<'PY'
import json
import pathlib
import sys

root_dir = pathlib.Path(sys.argv[1])
page_map_file = pathlib.Path(sys.argv[2])
output_mode = sys.argv[3]
pages_dir = root_dir / "pages"

if not page_map_file.exists():
    raise SystemExit(f"找不到 page map: {page_map_file}")

with page_map_file.open("r", encoding="utf-8") as f:
    data = json.load(f)

mapped = {k: v for k, v in data.items() if not k.startswith("_")}
mapped_paths = set(mapped.keys())

all_files = []
non_empty_files = []
empty_files = []

for path in pages_dir.rglob("*"):
    if not path.is_file():
        continue
    if path.name == ".gitkeep":
        continue
    rel = path.relative_to(root_dir).as_posix()
    all_files.append(rel)
    if path.stat().st_size > 0:
        non_empty_files.append(rel)
    else:
        empty_files.append(rel)

non_empty_files = sorted(non_empty_files)
empty_files = sorted(empty_files)

canonical_candidates = sorted(p for p in non_empty_files if p.endswith(".html"))
legacy_non_html = sorted(p for p in non_empty_files if not p.endswith(".html"))

mapped_non_empty = sorted(p for p in mapped_paths if p in non_empty_files)
mapped_missing_or_empty = sorted(p for p in mapped_paths if p not in non_empty_files)

unmapped_non_empty_html = sorted(p for p in canonical_candidates if p not in mapped_paths)
unmapped_non_empty_non_html = sorted(p for p in legacy_non_html if p not in mapped_paths)

report = {
    "summary": {
        "mapped_total": len(mapped_paths),
        "mapped_non_empty": len(mapped_non_empty),
        "mapped_missing_or_empty": len(mapped_missing_or_empty),
        "non_empty_total": len(non_empty_files),
        "non_empty_html": len(canonical_candidates),
        "non_empty_legacy_non_html": len(legacy_non_html),
        "unmapped_non_empty_html": len(unmapped_non_empty_html),
        "unmapped_non_empty_non_html": len(unmapped_non_empty_non_html),
        "empty_non_gitkeep": len(empty_files),
    },
    "mapped_non_empty": mapped_non_empty,
    "mapped_missing_or_empty": mapped_missing_or_empty,
    "unmapped_non_empty_html": unmapped_non_empty_html,
    "unmapped_non_empty_non_html": unmapped_non_empty_non_html,
    "empty_non_gitkeep": empty_files,
}

if output_mode == "json":
    print(json.dumps(report, ensure_ascii=False, indent=2))
    raise SystemExit(0)

print("=== Page Coverage Audit ===")
print(f"Root: {root_dir}")
print()

for key, value in report["summary"].items():
    print(f"{key}: {value}")

print("\n--- mapped_missing_or_empty ---")
if report["mapped_missing_or_empty"]:
    for item in report["mapped_missing_or_empty"]:
        print(item)
else:
    print("(none)")

print("\n--- unmapped_non_empty_html ---")
if report["unmapped_non_empty_html"]:
    for item in report["unmapped_non_empty_html"]:
        print(item)
else:
    print("(none)")

print("\n--- unmapped_non_empty_non_html (legacy/manual fragments) ---")
if report["unmapped_non_empty_non_html"]:
    for item in report["unmapped_non_empty_non_html"]:
        print(item)
else:
    print("(none)")

print("\n--- empty_non_gitkeep ---")
if report["empty_non_gitkeep"]:
    for item in report["empty_non_gitkeep"]:
        print(item)
else:
    print("(none)")
PY
