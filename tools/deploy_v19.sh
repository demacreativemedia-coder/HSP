#!/usr/bin/env bash
# Deploy only from a verified full Laravel installation, not from the source overlay.
set -Eeuo pipefail
ROOT="${1:-}"; [ -n "$ROOT" ] && [ -f "$ROOT/artisan" ] || { echo "Usage: bash tools/deploy_v19.sh /absolute/path/to/full-laravel-app" >&2; exit 2; }
ROOT="$(cd "$ROOT" && pwd)"
[ -f "$ROOT/vendor/autoload.php" ] || { echo 'Composer vendor missing. STOP.' >&2; exit 2; }
[ -f "$ROOT/.env" ] || { echo 'Production .env missing. STOP.' >&2; exit 2; }
php "$(dirname "$0")/production_readiness.php" "$ROOT" || { echo 'Preflight failed. STOP.' >&2; exit 2; }
cd "$ROOT"
if [ "${CONFIRM_DEPLOY:-}" != 'YES' ]; then
  echo 'Dry run only: no migration or cache mutation. Set CONFIRM_DEPLOY=YES after taking an off-server database backup.'
  php artisan about --only=environment
  php artisan migrate:status
  exit 0
fi
[ "${BACKUP_CONFIRMED:-}" = 'YES' ] || { echo 'Confirm off-server database backup: BACKUP_CONFIRMED=YES' >&2; exit 2; }
php artisan down --retry=60
restore(){ php artisan up || true; }
trap restore EXIT
php artisan migrate --force
php artisan optimize:clear
php artisan config:cache
php artisan view:cache
php artisan route:cache
php artisan storage:link || true
php artisan up
trap - EXIT
echo 'Deploy commands finished; manual HTTP smoke tests and security checks are still mandatory.'
