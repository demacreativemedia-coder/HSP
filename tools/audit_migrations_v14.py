#!/usr/bin/env python3
"""Static migration/schema cross-check. Not a substitute for running migrations."""
from pathlib import Path
import re,sys
root=Path(__file__).resolve().parents[1]
migrations=list((root/'database/migrations').glob('*.php'))
created={name for p in migrations for name in re.findall(r"Schema::create\(\s*['\"]([^'\"]+)",p.read_text())}
required={'leads','lead_activities','programs','site_settings','registrations','media_assets'}
missing=sorted(required-created)
print('Migration files:',len(migrations),'Tables created:',len(created))
print('Required tables:', ', '.join(sorted(required)))
if missing:
 print('FAIL missing tables:', ', '.join(missing));sys.exit(1)
print('PASS static schema table check (runtime not tested)')
