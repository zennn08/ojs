<?php
$contexts = ['jsep', 'japt'];

$mysqli = new mysqli('127.0.0.1', 'root', '', 'ojs_agrobisnis');
if ($mysqli->connect_error) {
    die("FAIL: DB connect error: " . $mysqli->connect_error . "\n");
}

$res = $mysqli->query("SELECT path, enabled FROM journals");
if (!$res) {
    die("FAIL: Could not query journals table: " . $mysqli->error . "\n");
}

$found_paths = [];
while ($row = $res->fetch_assoc()) {
    $found_paths[] = $row['path'];
}

foreach ($contexts as $ctx) {
    if (!in_array($ctx, $found_paths)) {
        die("FAIL: Journal context '$ctx' not found in database journals table.\n");
    }
}

echo "PASS: Multi-journal contexts ('jsep', 'japt') registered successfully.\n";
exit(0);
