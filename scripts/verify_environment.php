<?php
$required_extensions = ['intl', 'mbstring', 'gd', 'mysqli', 'pdo_mysql', 'fileinfo', 'xml', 'zip', 'curl'];
$missing = [];
foreach ($required_extensions as $ext) {
    if (!extension_loaded($ext)) {
        $missing[] = $ext;
    }
}

if (!empty($missing)) {
    echo "FAIL: Missing PHP extensions: " . implode(', ', $missing) . "\n";
    exit(1);
}

echo "PASS: All required PHP extensions loaded.\n";

$memory_limit = ini_get('memory_limit');
$upload_max = ini_get('upload_max_filesize');
echo "INFO: memory_limit = $memory_limit, upload_max_filesize = $upload_max\n";
exit(0);
