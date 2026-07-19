#!/usr/bin/env bash
set -euo pipefail

script_dir="$(cd "$(dirname "$0")" && pwd)"

printf '%s\n' 'NOTICE  v3 架構已退役；改執行現行 v4 驗收。' >&2
exec bash "$script_dir/test-site-v4-architecture-local.sh" "$@"
