<?php
$mysqli = new mysqli('127.0.0.1', 'root', '', 'ojs_agrobisnis');
if ($mysqli->connect_error) {
    die("Connect error: " . $mysqli->connect_error . "\n");
}

// 1. Update site theme
$mysqli->query("UPDATE site_settings SET setting_value = 'agrobisnis' WHERE setting_name = 'themePluginPath'");
echo "Site theme set to 'agrobisnis'.\n";

// 2. Update journal themes
$res = $mysqli->query("SELECT journal_id, path FROM journals");
while ($r = $res->fetch_assoc()) {
    $jid = (int)$r['journal_id'];
    $path = $r['path'];
    $mysqli->query("INSERT INTO journal_settings (journal_id, locale, setting_name, setting_value) 
        VALUES ($jid, '', 'themePluginPath', 'agrobisnis') 
        ON DUPLICATE KEY UPDATE setting_value = 'agrobisnis'");
    echo "Journal '$path' (ID $jid) theme set to 'agrobisnis'.\n";
}

// 3. Clear OJS template cache
$cacheDir = dirname(__DIR__) . '/public_html/cache/t_compile';
if (is_dir($cacheDir)) {
    $files = glob("$cacheDir/*");
    foreach ($files as $f) {
        if (is_file($f)) @unlink($f);
    }
    echo "Cleared template cache.\n";
}
