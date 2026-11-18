<?php
/** Run: php tools/production_readiness.php /path/to/laravel */
$root = $argv[1] ?? dirname(__DIR__);
$checks = [
    'Laravel artisan' => is_file($root.'/artisan'),
    'Composer dependencies' => is_file($root.'/vendor/autoload.php'),
    'Environment file' => is_file($root.'/.env'),
    'App key configured' => false,
    'Production debug disabled' => false,
    'Public document root available' => is_file($root.'/public/index.php'),
    'Writable storage' => is_dir($root.'/storage') && is_writable($root.'/storage'),
    'Writable cache' => is_dir($root.'/bootstrap/cache') && is_writable($root.'/bootstrap/cache'),
    'PHP 8.3+' => PHP_VERSION_ID >= 80300,
    'PDO extension' => extension_loaded('pdo'),
    'mbstring extension' => extension_loaded('mbstring'),
    'openssl extension' => extension_loaded('openssl'),
    'XML extension' => extension_loaded('xml'),
];
$env = is_file($root.'/.env') ? file_get_contents($root.'/.env') : '';
if ($env !== '') {
    preg_match('/^APP_KEY\s*=\s*(.*)$/m', $env, $m);
    $key = trim($m[1] ?? '', " \t\r\n\"'");
    $checks['App key configured'] = $key !== '' && $key !== 'base64:';
    $checks['Production debug disabled'] = (bool)preg_match('/^APP_DEBUG\s*=\s*false\s*$/mi', $env);
}
$fail = 0;
foreach ($checks as $label => $ok) { echo ($ok ? '[OK]   ' : '[FAIL] ').$label.PHP_EOL; if (!$ok) $fail++; }
echo "\n".($fail ? "NOT READY: $fail checks failed" : 'BASIC PREFLIGHT PASSED (runtime and security tests still required)').PHP_EOL;
exit($fail ? 1 : 0);
