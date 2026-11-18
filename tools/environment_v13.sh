#!/usr/bin/env bash
set -u
printf 'LPK Hikari — local environment check (V13)\n'
if command -v php >/dev/null 2>&1; then
  php -v | head -n 1
  php -r 'echo "PHP >= 8.3: ", (PHP_VERSION_ID >= 80300 ? "YES" : "NO"), PHP_EOL;'
  for ext in pdo mbstring openssl tokenizer xml ctype fileinfo; do
    php -r 'echo "'"$ext"': ", extension_loaded("'"$ext"'") ? "OK" : "MISSING", PHP_EOL;'
  done
else echo 'PHP CLI: MISSING'; fi
if command -v composer >/dev/null 2>&1; then composer --version; else echo 'Composer: MISSING (runtime integration unavailable)'; fi
if command -v sqlite3 >/dev/null 2>&1; then sqlite3 --version; else echo 'sqlite3 CLI: unavailable (PHP PDO SQLite may still work)'; fi
