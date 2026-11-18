#!/usr/bin/env python3
"""Non-invasive source audit; cannot replace penetration testing or runtime checks."""
from pathlib import Path
import re,sys
root=Path(sys.argv[1]).resolve() if len(sys.argv)>1 else Path(__file__).resolve().parents[1]
issues=[]
for f in root.rglob('*'):
    if not f.is_file() or any(p in f.parts for p in ['vendor','.git','node_modules']):continue
    if f.name=='.env': issues.append('Sensitive .env included in source package: '+str(f.relative_to(root)))
    if f.suffix=='.php' and f.name not in ('security_preflight_v19.py',):
        s=f.read_text(errors='replace')
        if re.search(r'\b(?:eval|shell_exec|passthru)\s*\(',s):issues.append('Review dangerous execution in '+str(f.relative_to(root)))
for f in (root/'public').rglob('*') if (root/'public').exists() else []:
    if f.is_file() and (f.name=='.env' or f.suffix in ('.sql','.sqlite','.bak')):issues.append('Sensitive public file: '+str(f.relative_to(root)))
print('Security source audit:', 'WARNINGS' if issues else 'No flagged patterns (not a security certification)')
for x in issues:print('-',x)
sys.exit(bool(issues))
