#!/usr/bin/env python3
"""Offline structural audit; does not substitute for Laravel runtime tests."""
from pathlib import Path
import re, sys
root=Path(__file__).resolve().parent.parent
routes=(root/'routes/web.php').read_text()
controllers={p.stem:p for p in (root/'app/Http/Controllers').glob('*.php')}
errors=[]; checked=0
# Validate all route controller class/method pairs (both imported and FQCN)
for klass,method in re.findall(r'\[([\\\w]+)::class\s*,\s*[\'\"](\w+)[\'\"]\]',routes):
    name=klass.split('\\')[-1]
    p=controllers.get(name)
    checked+=1
    if not p: errors.append(f'Controller missing: {name}::{method}')
    elif not re.search(r'function\s+'+re.escape(method)+r'\s*\(',p.read_text()): errors.append(f'Method missing: {name}::{method}')
# Duplicate route names can silently shadow routes.
names=re.findall(r'->name\(\s*[\'\"]([^\'\"]+)[\'\"]\s*\)',routes)
# Distinguish group prefixes from terminal names by checking trailing group chain.
terminal=[]
for line in routes.splitlines():
    if 'Route::' in line and '->name(' in line and not '->group(' in line:
        m=re.search(r'->name\([\'\"]([^\'\"]+)',line)
        if m: terminal.append(('admin.' if "prefix('admin')" not in line and line.lstrip().startswith('Route::') and False else '')+m.group(1))
# Structural file requirements
for rel in ['routes/web.php','database/seeders/HikariSeeder.php','tools/integrate_laravel12.sh']:
    if not (root/rel).exists(): errors.append('Missing '+rel)
print(f'Controller action references inspected: {checked}')
print(f'Controller files: {len(controllers)}; migrations: {len(list((root/"database/migrations").glob("*.php")))}')
for error in errors: print('ERROR:',error)
if errors: sys.exit(1)
print('PASS: controller action references resolve (offline only)')
