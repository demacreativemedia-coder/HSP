#!/usr/bin/env python3
"""Offline checks only; cannot substitute for executing Laravel tests."""
from pathlib import Path
import re, subprocess, sys
root=Path(__file__).resolve().parents[1]
errors=[]
php=list(root.rglob('*.php'))
for p in php:
    r=subprocess.run(['php','-l',str(p)],capture_output=True,text=True)
    if r.returncode: errors.append(f'PHP syntax: {p.relative_to(root)}: {r.stderr.strip()}')
for p in (root/'tools').glob('*.sh'):
    r=subprocess.run(['bash','-n',str(p)],capture_output=True,text=True)
    if r.returncode: errors.append(f'Bash syntax: {p.name}: {r.stderr.strip()}')
required=['routes/web.php','app/Http/Controllers/LeadController.php','app/Http/Controllers/AuthController.php','database/seeders/HikariSeeder.php','resources/views/site/page.blade.php','tools/integrate_laravel12.sh']
for name in required:
    if not (root/name).is_file(): errors.append('Missing: '+name)
# Detect accidental secrets in files distributed as source
for name in ['.env','database/database.sqlite','storage/logs/laravel.log','vendor/autoload.php']:
    if (root/name).exists(): errors.append('Do not ship: '+name)
# Require primary public routes and explicit rate limiting
routes=(root/'routes/web.php').read_text()
for pattern in ["Route::get('/'", "Route::get('/lp/kerja-jepang'", "Route::post('/konsultasi'", "Route::get('/admin/login'"]:
    if pattern not in routes: errors.append('Route missing: '+pattern)
if 'throttle:10,1' not in routes: errors.append('Consultation throttle absent')
print(f'Checked {len(php)} PHP files and {len(list((root/"tools").glob("*.sh")))} shell scripts')
if errors:
    for e in errors: print('FAIL:',e)
    sys.exit(1)
print('PASS: offline syntax and packaging quality gate')
print('NOT TESTED: Composer install, migrations, HTTP, auth, DB, analytics, browser UX')
