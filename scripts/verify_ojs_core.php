<?php
$web_root = 'D:/Project/agrobisnis-ojs/public_html';
$files_dir = 'D:/Project/agrobisnis-ojs/files_dir';

// 1. Verify config.inc.php exists
if (!file_exists("$web_root/config.inc.php")) {
    die("FAIL: config.inc.php not found in $web_root\n");
}

// 2. Verify files_dir is outside web_root
$real_web = realpath($web_root);
$real_files = realpath($files_dir);
if (str_starts_with($real_files, $real_web)) {
    die("FAIL: Security risk! files_dir is located inside public_html\n");
}

// 3. Test writable permissions on files_dir
$test_file = "$files_dir/write_test.tmp";
if (@file_put_contents($test_file, 'test') === false) {
    die("FAIL: files_dir is not writable\n");
}
unlink($test_file);

echo "PASS: OJS Core configuration and storage permissions validated.\n";
exit(0);
