from pathlib import Path
s=(Path(__file__).resolve().parents[1]/'routes/web.php').read_text()
for route in ['/program','/artikel']:
    lines=[l for l in s.splitlines() if "Route::get('"+route+"'," in l]
    assert len([l for l in lines if 'publicArticles' in l or 'SiteController::class' in l])==1, f"Unexpected route count for {route}: {len(lines)}"
    assert 'middleware(' not in next(l for l in lines if 'publicArticles' in l or 'SiteController::class' in l), f"Public route requires authorization: {route}"
print('PASS: public program and article routes are not authorization-gated')
