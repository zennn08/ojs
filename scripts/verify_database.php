<?php
$host = '127.0.0.1';
$user = 'root';
$pass = '';
$db   = 'ojs_agrobisnis';

$mysqli = new mysqli($host, $user, $pass);
if ($mysqli->connect_error) {
    die("FAIL: MySQL connection error: " . $mysqli->connect_error . "\n");
}

$res = $mysqli->query("SHOW DATABASES LIKE '$db'");
if ($res->num_rows === 0) {
    die("FAIL: Database '$db' does not exist.\n");
}

$res = $mysqli->query("SELECT DEFAULT_COLLATION_NAME FROM information_schema.SCHEMATA WHERE SCHEMA_NAME = '$db'");
$row = $res->fetch_assoc();
$collation = $row['DEFAULT_COLLATION_NAME'] ?? $row['default_collation_name'] ?? '';

if (!str_starts_with($collation, 'utf8mb4')) {
    die("FAIL: Expected collation utf8mb4, got: '$collation'\n");
}

echo "PASS: Database '$db' exists with valid collation ($collation).\n";
exit(0);
