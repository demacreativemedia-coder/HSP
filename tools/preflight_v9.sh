#!/usr/bin/env bash
set -eu
printf 'PHP: '; php -v | head -1 || true
printf 'Composer: '; if command -v composer >/dev/null; then composer --version; else echo 'NOT INSTALLED'; fi
php -r 'foreach (["pdo","pdo_sqlite","mbstring","openssl","tokenizer","xml","ctype","fileinfo"] as $e) echo $e.": ".(extension_loaded($e)?"OK":"MISSING").PHP_EOL;'
echo 'PHP files syntax scan:'
find "$(cd "$(dirname "$0")/.." && pwd)" -type f -name '*.php' -print0 | xargs -0 -n1 php -l | grep -v 'No syntax errors' || true
