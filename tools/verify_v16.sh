#!/usr/bin/env bash
set -Eeuo pipefail
ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
cd "$ROOT"
python3 tools/audit_routes_v12.py
python3 tools/audit_v13.py
python3 tools/audit_migrations_v14.py
find app database routes tests -type f -name '*.php' -print0 | while IFS= read -r -d '' f; do php -l "$f" >/dev/null; done
bash -n tools/integrate_laravel12.sh
printf 'Static checks passed. Laravel runtime tests must run separately.\n'
