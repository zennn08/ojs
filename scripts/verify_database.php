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

// 1. Check Schema Collation
$res = $mysqli->query("SELECT DEFAULT_COLLATION_NAME FROM information_schema.SCHEMATA WHERE SCHEMA_NAME = '$db'");
$row = $res->fetch_assoc();
$collation = $row['DEFAULT_COLLATION_NAME'] ?? $row['default_collation_name'] ?? '';
if (!str_starts_with($collation, 'utf8mb4')) {
    die("FAIL: Expected schema collation utf8mb4, got: '$collation'\n");
}

// 2. Check All Tables Collation
$res = $mysqli->query("SELECT TABLE_NAME, TABLE_COLLATION FROM information_schema.TABLES WHERE TABLE_SCHEMA = '$db' AND TABLE_TYPE = 'BASE TABLE'");
$non_utf8mb4_tables = [];
while ($row = $res->fetch_assoc()) {
    $tblCollation = $row['TABLE_COLLATION'] ?? '';
    if (!str_starts_with($tblCollation, 'utf8mb4')) {
        $non_utf8mb4_tables[] = $row['TABLE_NAME'] . " ($tblCollation)";
    }
}

if (!empty($non_utf8mb4_tables)) {
    die("FAIL: Found tables with non-utf8mb4 collation: " . implode(', ', array_slice($non_utf8mb4_tables, 0, 5)) . " (Total: " . count($non_utf8mb4_tables) . ")\n");
}

echo "PASS: Database '$db' and all " . $res->num_rows . " tables exist with valid utf8mb4 collation.\n";
exit(0);
