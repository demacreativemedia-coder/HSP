#!/usr/bin/env python3
"""Offline audit of named routes referenced by Blade; no Laravel runtime required."""
from pathlib import Path
import re,sys
root=Path(__file__).resolve().parents[1]
source=(root/'routes/web.php').read_text()
# Route group prefixes are lexical, based on curly-brace nesting.
# Match only route definitions and group statements (not general PHP statements).
route_names=set(); stack=[]; depth=0
for lineno,line in enumerate(source.splitlines(),1):
    stripped=line.strip()
    if '->group(function()' in stripped:
        prefix=re.search(r"->name\(['\"]([^'\"]+)['\"]\)",stripped)
        inherited=''.join(p for _,p in stack)
        stack.append((depth+stripped.count('{')-stripped.count('}'),prefix.group(1) if prefix else ''))
    else:
        match=re.search(r"Route::(?:get|post|put|patch|delete|match|any)\s*\(",stripped)
        if match:
            name=re.search(r"->name\(['\"]([^'\"]+)['\"]\)",stripped)
            if name: route_names.add(''.join(p for _,p in stack)+name.group(1))
    depth+=line.count('{')-line.count('}')
    while stack and depth<stack[-1][0]:stack.pop()
blade='\n'.join(p.read_text() for p in (root/'resources/views').rglob('*.blade.php'))
used=set(re.findall(r"\broute\(\s*['\"]([\w.\-]+)['\"]",blade))
missing=sorted(used-route_names)
print(f'Named routes declared: {len(route_names)}; referenced by Blade: {len(used)}')
for name in missing:print('ERROR missing named route:',name)
print('PASS: Blade route references resolve' if not missing else 'FAIL: missing routes')
sys.exit(bool(missing))
