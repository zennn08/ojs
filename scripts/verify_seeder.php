<?php
$mysqli = new mysqli('127.0.0.1', 'root', '', 'ojs_agrobisnis');
if ($mysqli->connect_error) {
    die("FAIL: DB connect error: " . $mysqli->connect_error . "\n");
}

// 1. Check published issues
$res = $mysqli->query("SELECT issue_id, journal_id, volume, number, year, published FROM issues WHERE published = 1");
if (!$res || $res->num_rows < 2) {
    die("FAIL: Expected at least 2 published issues, found: " . ($res ? $res->num_rows : 0) . "\n");
}

// 2. Check current issue set on journals
$res = $mysqli->query("SELECT journal_id, path, current_issue_id FROM journals WHERE current_issue_id IS NOT NULL");
if (!$res || $res->num_rows < 2) {
    die("FAIL: Expected 2 journals with active current_issue_id, found: " . ($res ? $res->num_rows : 0) . "\n");
}

// 3. Check published submissions
$res = $mysqli->query("SELECT submission_id FROM submissions WHERE status = 3");
if (!$res || $res->num_rows < 6) {
    die("FAIL: Expected at least 6 published submissions, found: " . ($res ? $res->num_rows : 0) . "\n");
}

// 4. Check publications with title and abstract
$res = $mysqli->query("SELECT COUNT(DISTINCT p.publication_id) as total 
    FROM publications p 
    JOIN publication_settings pst ON p.publication_id = pst.publication_id AND pst.setting_name = 'title'
    JOIN publication_settings psa ON p.publication_id = psa.publication_id AND psa.setting_name = 'abstract'
    WHERE p.status = 3");
$row = $res->fetch_assoc();
if ((int)$row['total'] < 6) {
    die("FAIL: Expected 6 publications with title & abstract, found: " . $row['total'] . "\n");
}

// 5. Check authors
$res = $mysqli->query("SELECT COUNT(DISTINCT author_id) as total FROM authors");
$row = $res->fetch_assoc();
if ((int)$row['total'] < 6) {
    die("FAIL: Expected at least 6 authors registered, found: " . $row['total'] . "\n");
}

// 6. Check HTTP responses for article rendering
$jsepContent = @file_get_contents('http://localhost/jsep');
if (!str_contains($jsepContent, 'Rantai Pasok Beras Organik')) {
    die("FAIL: Article title 'Rantai Pasok Beras Organik' not found in http://localhost/jsep HTML\n");
}

$japtContent = @file_get_contents('http://localhost/japt');
if (!str_contains($japtContent, 'Pemupukan Presisi Berbasis Sensor NPK')) {
    die("FAIL: Article title 'Pemupukan Presisi Berbasis Sensor NPK' not found in http://localhost/japt HTML\n");
}

echo "PASS: Agribusiness articles seeded, published, and verified on public journal pages.\n";
exit(0);
