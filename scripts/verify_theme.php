<?php
$mysqli = new mysqli('127.0.0.1', 'root', '', 'ojs_agrobisnis');
if ($mysqli->connect_error) {
    die("FAIL: DB connect error: " . $mysqli->connect_error . "\n");
}

// 1. Verify theme setting in site_settings
$res = $mysqli->query("SELECT setting_value FROM site_settings WHERE setting_name = 'themePluginPath'");
$row = $res ? $res->fetch_assoc() : null;
$siteTheme = $row['setting_value'] ?? '';
if ($siteTheme !== 'agrobisnis') {
    die("FAIL: Site themePluginPath is not 'agrobisnis' (current: '$siteTheme')\n");
}

// 2. Verify theme setting in journal_settings for both journals
$res = $mysqli->query("SELECT j.path, js.setting_value FROM journals j JOIN journal_settings js ON j.journal_id = js.journal_id WHERE js.setting_name = 'themePluginPath'");
$journalThemes = [];
while ($r = $res->fetch_assoc()) {
    $journalThemes[$r['path']] = $r['setting_value'];
}

foreach (['jsep', 'japt'] as $path) {
    if (($journalThemes[$path] ?? '') !== 'agrobisnis') {
        die("FAIL: Journal '$path' theme is not 'agrobisnis' (current: '" . ($journalThemes[$path] ?? 'none') . "')\n");
    }
}

// 3. Verify CSS file exists
$cssPath = dirname(__DIR__) . '/public_html/plugins/themes/agrobisnis/styles/custom.css';
if (!file_exists($cssPath)) {
    die("FAIL: Custom CSS file not found at $cssPath\n");
}

echo "PASS: Agrobisnis theme configured and files verified.\n";
exit(0);
