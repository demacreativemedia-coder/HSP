#!/usr/bin/env python3
"""Offline structural audit; not a substitute for Laravel runtime tests."""
from pathlib import Path
import re, sys
root=Path(__file__).resolve().parents[1]
errors=[]; warnings=[]
routes=(root/'routes/web.php').read_text()
for cls in sorted(set(re.findall(r'([A-Za-z]+Controller)::class',routes))):
    if not (root/'app/Http/Controllers'/f'{cls}.php').exists():errors.append(f'Missing controller: {cls}')
for view in sorted(set(re.findall(r"view\(['\"]([\w.\-]+)['\"]", ''.join(p.read_text() for p in (root/'app/Http/Controllers').glob('*.php')) + routes))):
    if not (root/'resources/views'/('/'.join(view.split('.'))+'.blade.php')).exists():errors.append(f'Missing Blade view: {view}')
for model in sorted(set(re.findall(r'use App\\Models\\(\w+);', ''.join(p.read_text() for p in (root/'app/Http/Controllers').glob('*.php'))))):
    if not (root/'app/Models'/f'{model}.php').exists():errors.append(f'Missing model: {model}')
for route_name in sorted(set(re.findall(r"route\(['\"]([\w.\-]+)['\"]", ''.join(p.read_text() for p in (root/'resources/views').rglob('*.blade.php'))))):
    if not re.search(r"->name\(['\"]"+re.escape(route_name.split('.')[-1])+r"['\"]\)",routes):warnings.append(f'Check route name: {route_name}')
if not (root/'tools/integrate_laravel12.sh').exists():errors.append('Missing integration script')
print(f'Controllers: {len(list((root/"app/Http/Controllers").glob("*.php")))}; Blade views: {len(list((root/"resources/views").rglob("*.blade.php")))}')
for e in errors:print('ERROR:',e)
for w in warnings:print('WARN:',w)
print('Static audit:', 'FAILED' if errors else 'PASSED', '(runtime not tested)')
sys.exit(1 if errors else 0)
