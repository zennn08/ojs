# Multi-Journal OJS Agribisnis Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Build, configure, and verify a complete multi-journal Open Journal Systems (OJS 3.5 LTS) platform for the agribusiness domain on Windows with Laragon, including PHP 8.3 extensions tuning, phpMyAdmin integration, clean URL virtual hosts, and 2 pilot journals.

**Architecture:** Single-codebase OJS 3.5 LTS deployment under `D:\Project\agrobisnis-ojs\public_html` with private storage separated at `D:\Project\agrobisnis-ojs\files_dir`. Apache Laragon serves `http://agrobisnis.test` via Virtual Host and routes journals via native sub-path routing (`/jsep` and `/japt`) backed by MySQL 8.4 and managed with phpMyAdmin.

**Tech Stack:** OJS 3.5 LTS, PHP 8.3.33, MySQL 8.4.3, Apache 2.4, phpMyAdmin 5.2.x, Laragon v8.7.0, PowerShell.

**Spec:** `docs/superpowers/specs/2026-10-21-multi-journal-ojs-agrobisnis-design.md`

## Global Constraints

- Platform: Windows 11 with Laragon at `C:\laragon`
- Project Root: `D:\Project\agrobisnis-ojs`
- Web Root: `D:\Project\agrobisnis-ojs\public_html`
- Private Storage: `D:\Project\agrobisnis-ojs\files_dir`
- Domain: `http://agrobisnis.test`
- Database Name: `ojs_agrobisnis` (MySQL 8.4, `utf8mb4_unicode_ci`)
- PHP Version: PHP 8.3.33 with extensions `intl`, `mbstring`, `gd`, `mysqli`, `pdo_mysql`, `fileinfo`, `xml`, `zip`, `curl`

## Review Focus

1. **PHP Extension Availability:** If `intl` or `fileinfo` is missing or commented out in `php.ini`, OJS white-screens on first boot. Verified before OJS extraction.
2. **Private File Isolation:** If `files_dir` is accidentally inside `public_html`, uploaded unpublished manuscripts can be accessed directly via URL traversal. Verified strictly outside web root.
3. **Database Charset:** If MySQL default charset is `latin1` instead of `utf8mb4_unicode_ci`, special symbols and international characters in abstracts corrupt. Verified by database collation test.
4. **Apache Rewrite & Clean URL:** If `mod_rewrite` or `AllowOverride All` is misconfigured in Virtual Host, `/jsep` returns 404 Not Found. Verified via HTTP endpoint testing.
5. **Windows Path Separators in OJS Config:** If backslashes `\` are used in `config.inc.php` instead of forward slashes `/`, path parsing fails silently on Windows PHP. Verified in configuration step.

---

### Task 1: Environment & PHP Verification & phpMyAdmin Integration

**Files:**
- Modify: `C:\laragon\bin\php\php-8.3.33-Win32-vs16-x64\php.ini`
- Create: `C:\laragon\etc\apache2\alias\phpmyadmin.conf`
- Create: `scripts/verify_environment.php`
- Directory: `C:\laragon\etc\apps\phpMyAdmin`

**Interfaces:**
- Consumes: Laragon installation at `C:\laragon`
- Produces: Fully enabled PHP 8.3 extensions and working phpMyAdmin at `http://localhost/phpmyadmin`

- [ ] **Step 1: Write test script to verify required PHP extensions and phpMyAdmin availability**

Create `scripts/verify_environment.php`:
```php
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
```

- [ ] **Step 2: Run verification script to check existing environment state**

Run:
```powershell
C:\laragon\bin\php\php-8.3.33-Win32-vs16-x64\php.exe scripts/verify_environment.php
```
Expected: Note any missing extensions or low memory limits.

- [ ] **Step 3: Update `php.ini` with required extensions and memory limits**

Ensure the following directives are active (uncommented) in `C:\laragon\bin\php\php-8.3.33-Win32-vs16-x64\php.ini`:
```ini
extension=curl
extension=fileinfo
extension=gd
extension=intl
extension=mbstring
extension=mysqli
extension=openssl
extension=pdo_mysql
extension=xml
extension=zip

memory_limit = 512M
upload_max_filesize = 64M
post_max_size = 64M
max_execution_time = 300
max_input_vars = 5000
```

- [ ] **Step 4: Download and deploy phpMyAdmin to Laragon apps**

Download official phpMyAdmin 5.2.2 (or latest 5.2.x all-languages), extract to `C:\laragon\etc\apps\phpMyAdmin`, and create `C:\laragon\etc\apache2\alias\phpmyadmin.conf`:
```apache
Alias /phpmyadmin "C:/laragon/etc/apps/phpMyAdmin/"
<Directory "C:/laragon/etc/apps/phpMyAdmin/">
    Options Indexes FollowSymLinks MultiViews
    AllowOverride all
    Require all granted
</Directory>
```

- [ ] **Step 5: Run verification tests for PHP and phpMyAdmin**

Run:
```powershell
C:\laragon\bin\php\php-8.3.33-Win32-vs16-x64\php.exe scripts/verify_environment.php
```
Expected: `PASS: All required PHP extensions loaded.`

Restart Laragon Apache service and test phpMyAdmin HTTP response:
```powershell
Invoke-WebRequest -Uri "http://localhost/phpmyadmin" -UseBasicParsing | Select-Object StatusCode
```
Expected: `StatusCode: 200`

- [ ] **Step 6: Commit test script and documentation**

```bash
git add scripts/verify_environment.php
git commit -m "chore: add environment verification test script"
```

---

### Task 2: Project Scaffolding, Apache Virtual Host & MySQL Database Setup

**Files:**
- Create: `D:\Project\agrobisnis-ojs\public_html\`
- Create: `D:\Project\agrobisnis-ojs\files_dir\`
- Create: `D:\Project\agrobisnis-ojs\logs\`
- Create: `C:\laragon\etc\apache2\sites-enabled\auto.agrobisnis.test.conf`
- Create: `scripts/verify_database.php`

**Interfaces:**
- Consumes: MySQL 8.4 running on port 3306, Apache 2.4 on port 80
- Produces: Database `ojs_agrobisnis` created with `utf8mb4_unicode_ci`, domain `http://agrobisnis.test` mapped to `public_html`

- [ ] **Step 1: Write test script to verify database creation and Virtual Host routing**

Create `scripts/verify_database.php`:
```php
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

$res = $mysqli->query("SELECT default_collation_name FROM information_schema.SCHEMATA WHERE schema_name = '$db'");
$row = $res->fetch_assoc();
if (!str_starts_with($row['default_collation_name'], 'utf8mb4')) {
    die("FAIL: Expected collation utf8mb4, got: " . $row['default_collation_name'] . "\n");
}

echo "PASS: Database '$db' exists with valid collation (" . $row['default_collation_name'] . ").\n";
```

- [ ] **Step 2: Create directory scaffold**

Create directories:
- `D:\Project\agrobisnis-ojs\public_html`
- `D:\Project\agrobisnis-ojs\files_dir`
- `D:\Project\agrobisnis-ojs\logs`

Create temporary `public_html/index.php` for preliminary Virtual Host check:
```php
<?php echo "Agrobisnis OJS Vhost Ready";
```

- [ ] **Step 3: Configure Laragon Apache Virtual Host and Windows Hosts**

Create `C:\laragon\etc\apache2\sites-enabled\auto.agrobisnis.test.conf`:
```apache
<VirtualHost *:80>
    DocumentRoot "D:/Project/agrobisnis-ojs/public_html"
    ServerName agrobisnis.test
    ServerAlias *.agrobisnis.test
    <Directory "D:/Project/agrobisnis-ojs/public_html">
        AllowOverride All
        Require all granted
        Options -Indexes +FollowSymLinks
    </Directory>
</VirtualHost>
```

Ensure `C:\Windows\System32\drivers\etc\hosts` contains:
```
127.0.0.1 agrobisnis.test
```

- [ ] **Step 4: Create MySQL database `ojs_agrobisnis`**

Execute database creation query:
```powershell
C:\laragon\bin\mysql\mysql-8.4.3-winx64\bin\mysql.exe -u root -e "CREATE DATABASE IF NOT EXISTS ojs_agrobisnis CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

- [ ] **Step 5: Run tests for database and Virtual Host**

Run:
```powershell
C:\laragon\bin\php\php-8.3.33-Win32-vs16-x64\php.exe scripts/verify_database.php
```
Expected: `PASS: Database 'ojs_agrobisnis' exists with valid collation.`

Restart Apache and test Virtual Host:
```powershell
Invoke-WebRequest -Uri "http://agrobisnis.test" -UseBasicParsing | Select-Object -ExpandProperty Content
```
Expected: Contains `Agrobisnis OJS Vhost Ready`.

- [ ] **Step 6: Commit test script**

```bash
git add scripts/verify_database.php
git commit -m "feat: scaffold workspace, apache vhost and mysql database"
```

---

### Task 3: OJS 3.5 LTS Deployment & Core Configuration

**Files:**
- Create: `D:\Project\agrobisnis-ojs\public_html\config.inc.php`
- Create: `D:\Project\agrobisnis-ojs\public_html\.htaccess`
- Create: `scripts/verify_ojs_core.php`

**Interfaces:**
- Consumes: OJS 3.5 LTS release archive, MySQL database `ojs_agrobisnis`, directory `files_dir/`
- Produces: Fully installed and configured OJS application reachable at `http://agrobisnis.test`

- [ ] **Step 1: Write test script to verify OJS core deployment and security bounds**

Create `scripts/verify_ojs_core.php`:
```php
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
```

- [ ] **Step 2: Obtain and extract OJS 3.5 LTS package**

Download official PKP OJS 3.5 LTS release archive (`ojs-3.5.0-x.tar.gz`) from PKP repository, extract contents directly into `D:\Project\agrobisnis-ojs\public_html\`.

- [ ] **Step 3: Setup `.htaccess` for Clean URL rewriting**

Create `D:\Project\agrobisnis-ojs\public_html\.htaccess`:
```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteBase /

    # Exclude physical files and directories from rewriting
    RewriteCond %{REQUEST_FILENAME} -d [OR]
    RewriteCond %{REQUEST_FILENAME} -f
    RewriteRule ^ - [L]

    # Rewrite all other requests to index.php
    RewriteRule ^(.*)$ index.php/$1 [QSA,L]
</IfModule>
```

- [ ] **Step 4: Install and configure `config.inc.php`**

Execute OJS CLI installer or generate `config.inc.php` configured with:
- `installed = On`
- `base_url = "http://agrobisnis.test"`
- `files_dir = "D:/Project/agrobisnis-ojs/files_dir"`
- `connection_charset = "utf8mb4"`
- `database_driver = "mysqli"`
- `database_host = "127.0.0.1"`
- `database_user = "root"`
- `database_password = ""`
- `database_name = "ojs_agrobisnis"`
- `restful_urls = On`

- [ ] **Step 5: Run verification tests for OJS core**

Run:
```powershell
C:\laragon\bin\php\php-8.3.33-Win32-vs16-x64\php.exe scripts/verify_ojs_core.php
```
Expected: `PASS: OJS Core configuration and storage permissions validated.`

Perform HTTP test on base URL:
```powershell
Invoke-WebRequest -Uri "http://agrobisnis.test" -UseBasicParsing | Select-Object StatusCode
```
Expected: `StatusCode: 200`

- [ ] **Step 6: Commit core configuration and scripts**

```bash
git add scripts/verify_ojs_core.php public_html/.htaccess
git commit -m "feat: deploy OJS 3.5 LTS core and configure clean URLs"
```

---

### Task 4: Multi-Journal Hierarchy Setup (Portal Induk + JSEP & JAPT)

**Files:**
- Create: `scripts/verify_multi_journal.php`
- Configuration: Site level settings and 2 context journals in database `ojs_agrobisnis`

**Interfaces:**
- Consumes: Running OJS instance on `http://agrobisnis.test`
- Produces: Aggregator portal at `/`, Journal 1 at `/jsep`, Journal 2 at `/japt`

- [ ] **Step 1: Write test script to verify multi-journal endpoints and database contexts**

Create `scripts/verify_multi_journal.php`:
```php
<?php
$contexts = ['jsep', 'japt'];

// Check database contexts
$mysqli = new mysqli('127.0.0.1', 'root', '', 'ojs_agrobisnis');
if ($mysqli->connect_error) {
    die("FAIL: DB connect error: " . $mysqli->connect_error . "\n");
}

$res = $mysqli->query("SELECT path, enabled FROM journals");
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
```

- [ ] **Step 2: Configure Site-Level Portal Branding**

Set Site title: `Portal Jurnal Agribisnis Indonesia`.
Set Site introduction and enable journal listing display on homepage.

- [ ] **Step 3: Create Pilot Journal 1 (`jsep`)**

- Journal Title: `Jurnal Sosial Ekonomi Pertanian`
- URL Path: `jsep`
- Aims & Scope: Agribisnis pedesaan, rantai pasok pangan, dinamika kelembagaan petani, dan kebijakan pertanian.
- Primary Language: Indonesian (id_ID) / English (en_US).

- [ ] **Step 4: Create Pilot Journal 2 (`japt`)**

- Journal Title: `Jurnal Agroteknologi dan Pengolahan Hasil Pertanian`
- URL Path: `japt`
- Aims & Scope: Precision farming, inovasi pascapanen, rekayasa produk olahan hasil tani, dan keamanan pangan agribisnis.
- Primary Language: Indonesian (id_ID) / English (en_US).

- [ ] **Step 5: Run tests for multi-journal endpoints**

Run:
```powershell
C:\laragon\bin\php\php-8.3.33-Win32-vs16-x64\php.exe scripts/verify_multi_journal.php
```
Expected: `PASS: Multi-journal contexts ('jsep', 'japt') registered successfully.`

Test HTTP responses:
```powershell
$urls = @('http://agrobisnis.test', 'http://agrobisnis.test/jsep', 'http://agrobisnis.test/japt')
foreach ($u in $urls) {
    $res = Invoke-WebRequest -Uri $u -UseBasicParsing
    Write-Host "$u -> $($res.StatusCode)"
}
```
Expected: All return `200`.

- [ ] **Step 6: Commit multi-journal verification script**

```bash
git add scripts/verify_multi_journal.php
git commit -m "feat: configure multi-journal structure with jsep and japt contexts"
```
