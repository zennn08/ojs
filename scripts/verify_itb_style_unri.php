<?php
/**
 * scripts/verify_itb_style_unri.php
 *
 * TDD Verification script for the ITB-style directory UI adapted for
 * Universitas Riau (UNRI) Program Studi Agribisnis on OJS 3.5.
 */

$portalUrl = 'http://127.0.0.1/';
echo "==> Testing Portal UI at $portalUrl...\n";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $portalUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
curl_setopt($ch, CURLOPT_TIMEOUT, 15);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'User-Agent: UNRI-OJS-Verifier/1.0',
    'Host: localhost'
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlErr  = curl_error($ch);
curl_close($ch);

if ($response === false || $httpCode !== 200) {
    echo "[FAIL] Could not fetch portal (HTTP $httpCode). Error: $curlErr\n";
    exit(1);
}

echo "[PASS] Portal responded with HTTP 200 (" . strlen($response) . " bytes)\n";

$requiredMarkers = [
    'UNRI Agribusiness Journals' => 'Hero title "UNRI Agribusiness Journals"',
    'Universitas Riau'           => 'Institutional name "Universitas Riau"',
    'Kampus Bina Widya'          => 'Institutional address "Kampus Bina Widya"',
    'Jelajahi Jurnal'            => 'Hero action CTA "Jelajahi Jurnal"',
    'Kunjungi Jurnal'            => 'Card action CTA "Kunjungi Jurnal"',
    'Sosio-Ekonomi & Bisnis Pertanian' => 'Subject discipline card "Sosio-Ekonomi & Bisnis Pertanian"',
    'Agroteknologi'              => 'Discipline / card marker "Agroteknologi"',
    'GARUDA'                     => 'Footer indexing badge "GARUDA"',
    'SINTA'                      => 'Footer indexing badge "SINTA"',
    'id="themeToggle"'           => 'Navbar Dark/Light mode toggle button "themeToggle"',
    'data-theme'                 => 'Theme switching attribute "data-theme"'
];

$failedCount = 0;
foreach ($requiredMarkers as $marker => $label) {
    if (strpos($response, $marker) !== false) {
        echo "[PASS] Found: $label\n";
    } else {
        echo "[FAIL] Missing: $label\n";
        $failedCount++;
    }
}

if ($failedCount > 0) {
    echo "\n==> TDD State: RED ($failedCount assertions failed as expected before implementation).\n";
    exit(2);
}

echo "\n==> TDD State: GREEN (All ITB-style UNRI Agribisnis directory markers present).\n";
exit(0);
