#!/usr/bin/env bash
set -Eeuo pipefail
ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
TARGET="${1:-$PWD/hikari-laravel-app}"
mkdir -p "$(dirname "$TARGET")"
TARGET="$(cd "$(dirname "$TARGET")" && pwd)/$(basename "$TARGET")"
python3 "$ROOT/tools/audit_routes_v12.py"
python3 "$ROOT/tools/audit_v13.py"
python3 "$ROOT/tools/audit_migrations_v14.py"
command -v php >/dev/null || { echo 'ERROR: PHP CLI missing' >&2; exit 1; }
command -v composer >/dev/null || { echo 'ERROR: Composer 2 missing' >&2; exit 1; }
composer --version --no-ansi | grep -Eq 'Composer version 2\.' || { echo 'ERROR: Composer 2 required' >&2; exit 1; }
php -r 'foreach (["pdo", "mbstring", "openssl", "tokenizer", "xml"] as $ext) { if (!extension_loaded($ext)) {fwrite(STDERR,"Missing PHP extension: $ext\n"); exit(1);} }'
php -r 'if (PHP_VERSION_ID < 80300) {fwrite(STDERR,"PHP 8.3+ required\n");exit(1);}'
if [ -e "$TARGET" ]; then echo "ERROR: destination exists: $TARGET" >&2; exit 1; fi
composer create-project laravel/laravel "$TARGET" '^12.0' --no-interaction --prefer-dist
for folder in app routes database resources public tests; do
  if [ -d "$ROOT/$folder" ]; then cp -a "$ROOT/$folder/." "$TARGET/$folder/"; fi
done
php -r '
$f=$argv[1]; $s=file_get_contents($f);
$p="App\\Providers\\HikariAuthorizationServiceProvider::class";
if(strpos($s,$p)===false){
  $pos=strrpos($s,"];");
  if($pos===false){fwrite(STDERR,"Provider file not recognized\n");exit(1);}
  $s=substr_replace($s,"    ".$p.",\n",$pos,0);
  file_put_contents($f,$s);
}
' "$TARGET/bootstrap/providers.php"
cd "$TARGET"
php artisan key:generate --force
# Isolated test database; production database must be configured separately.
mkdir -p database
touch database/database.sqlite
# Persist local-only SQLite configuration; do not point this at production.
php -r '
$f=".env"; $s=file_get_contents($f);
foreach(["DB_CONNECTION"=>"sqlite","DB_DATABASE"=>getcwd()."/database/database.sqlite"] as $key=>$value){
 $line=$key."=".$value;
 if(preg_match("/^".$key."=.*$/m",$s)) $s=preg_replace("/^".$key."=.*$/m",$line,$s);
 else $s.="\n".$line;
}
file_put_contents($f,$s);
'
php artisan config:clear
php artisan optimize:clear
DB_CONNECTION=sqlite DB_DATABASE="$TARGET/database/database.sqlite" php artisan migrate --force
DB_CONNECTION=sqlite DB_DATABASE="$TARGET/database/database.sqlite" php artisan db:seed --class='Database\Seeders\HikariSeeder' --force
DB_CONNECTION=sqlite DB_DATABASE="$TARGET/database/database.sqlite" php artisan route:list
DB_CONNECTION=sqlite DB_DATABASE="$TARGET/database/database.sqlite" php artisan test --stop-on-failure
printf '\nV16 integration checks completed. This is a local test install, NOT production deployment.\n'

# Note: production readiness checks are separate and require real server settings.
