# Spesifikasi Desain: Multi-Jurnal Portal Agribisnis Berbasis OJS 3.5 LTS & Laragon

- **Tanggal**: 2026-10-21
- **Status**: Disetujui (Approved)
- **Ranah / Subjek**: Agribisnis & Ilmu Pertanian
- **Platform**: Open Journal Systems (OJS) 3.5 LTS
- **Lingkungan**: Windows 11 / Laragon v8.7.0 (Apache 2.4, PHP 8.3, MySQL 8.4)

---

## 1. Ringkasan Eksekutif & Tujuan

Membangun dan mengonfigurasi portal penerbitan jurnal ilmiah multi-jurnal (*multi-journal system*) untuk bidang **Agribisnis** menggunakan platform **Open Journal Systems (OJS) versi 3.5 LTS**. 

Sistem ini dirancang untuk:
1. Menjadi portal induk agregator jurnal-jurnal ilmiah di bawah sub-disiplin agribisnis.
2. Memfasilitasi alur kerja editorial independen per jurnal (manajemen dewan editor, reviewer, dan penulis).
3. Menggunakan satu instalasi basis kode (*single codebase, single database instance*) dengan pemetaan sub-path (*sub-path routing*) yang stabil dan aman di lingkungan Laragon.
4. Memasang dan mengintegrasikan **phpMyAdmin** ke dalam Laragon untuk pengelolaan database MySQL 8.4 yang fleksibel dan terpusat.

---

## 2. Arsitektur Komponen & Lingkungan Sistem

### 2.1 Stack Teknologi
* **Web Server**: Apache 2.4 (Virtual Host di Laragon)
* **Runtime PHP**: PHP 8.3.33 (64-bit VS16)
* **Database Engine**: MySQL 8.4.3 (InnoDB, default charset `utf8mb4`)
* **Database Manager**: phpMyAdmin 5.2.x (diintegrasikan di `C:\laragon\etc\apps\phpMyAdmin`)
* **Aplikasi OJS**: OJS 3.5.0 LTS (Long-Term Support resmi dari PKP)

### 2.2 Penyesuaian Ekstensi & Konfigurasi PHP (`php.ini`)
Modul-modul wajib untuk OJS yang diaktifkan pada `C:\laragon\bin\php\php-8.3.33-Win32-vs16-x64\php.ini`:
* `intl` — Internasionalisasi tanggal, bahasa, dan format naskah.
* `mbstring` — Penanganan karakter multibyte (UTF-8) pada judul/abstrak.
* `gd` — Pembuatan thumbnail cover jurnal, avatar, dan gambar artikel.
* `mysqli` dan `pdo_mysql` — Komunikasi database tingkat tinggi.
* `fileinfo` — Deteksi MIME type file naskah (DOCX, PDF, XLS).
* `xml`, `zip`, `curl` — Parsing XML (ekspor Crossref/DOAJ/PubMed) dan pemanggilan API eksternal.

Parameter performa PHP:
```ini
upload_max_filesize = 64M
post_max_size = 64M
memory_limit = 512M
max_execution_time = 300
max_input_vars = 5000
```

---

## 3. Struktur Direktori & Keamanan Berkas

Sesuai standar keamanan PKP OJS, file naskah submisi tidak boleh disimpan di direktori yang dapat diakses langsung oleh browser.

```
D:\Project\agrobisnis-ojs\
├── docs\
│   └── superpowers\specs\
│       └── 2026-10-21-multi-journal-ojs-agrobisnis-design.md
├── files_dir\                  # Private File Storage (DI LUAR WEB ROOT)
│   ├── journals\               # Berkas naskah, review, revisi per jurnal
│   └── site\                   # Berkas portal utama
├── public_html\                # Web Root (dapat diakses publik)
│   ├── api\
│   ├── classes\
│   ├── controllers\
│   ├── dbscripts\
│   ├── js\
│   ├── lib\
│   ├── locale\
│   ├── pages\
│   ├── plugins\
│   ├── public\                 # File publik (logo jurnal, favicon, CSS)
│   ├── styles\
│   ├── templates\
│   ├── .htaccess               # URL rewrite & routing
│   └── config.inc.php          # Konfigurasi utama OJS
└── logs\                       # Catatan log aplikasi & debug
```

---

## 4. Konfigurasi Domain, Virtual Host & Database

### 4.1 Virtual Host Laragon
Virtual host didefinisikan pada Apache Laragon (`C:\laragon\etc\apache2\sites-enabled\auto.agrobisnis-ojs.test.conf`):
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

### 4.2 Database MySQL
* **Nama Database**: `ojs_agrobisnis`
* **Collation**: `utf8mb4_unicode_ci`
* **User**: `root` (Local development) tanpa password default Laragon atau `ojs_admin`.

### 4.3 Konfigurasi Utama OJS (`public_html/config.inc.php`)
* `general.base_url = "http://agrobisnis.test"`
* `general.restful_urls = On` (Mengaktifkan clean URL tanpa `index.php`)
* `files.files_dir = "D:/Project/agrobisnis-ojs/files_dir"`
* `files.public_files_dir = "public"`
* `database.driver = "mysqli"`
* `database.collation = "utf8mb4_unicode_ci"`

---

## 5. Arsitektur Multi-Jurnal & Delegasi Peran

### 5.1 Hirarki Perutean (Sub-Path Routing)
1. **Portal Induk / Aggregator**:
   * URL: `http://agrobisnis.test/`
   * Menampilkan daftar seluruh jurnal yang tergabung, sambutan portal induk agribisnis, serta tautan pendaftaran akun terpadu.
2. **Jurnal Percontohan 1 (Sosial Ekonomi Pertanian)**:
   * URL: `http://agrobisnis.test/jsep`
   * Fokus / Scope: Agribisnis pedesaan, rantai pasok pangan, sosiologi pertanian, ekonomi sumberdaya.
3. **Jurnal Percontohan 2 (Agroteknologi & Pengolahan)**:
   * URL: `http://agrobisnis.test/japt`
   * Fokus / Scope: Teknologi budidaya presisi, penanganan pascapanen, pengolahan hasil pertanian bernilai tambah.

### 5.2 Hak Akses & Peran Pengguna
* **Site Administrator**: Mengontrol konfigurasi global, instalasi plugin bahasa/tema, dan penambahan entitas jurnal baru.
* **Journal Manager & Editor-in-Chief**: Memiliki otonomi penuh terhadap kebijakan publikasi, penerbitan edisi berkala (*issues*), dan penugasan reviewer naskah di jurnalnya masing-masing.
* **Reviewer & Author**: Terintegrasi secara *Single Sign-On (SSO)* pada level database, sehingga satu akun dapat mengajukan artikel di `jsep` sekaligus menjadi reviewer di `japt`.

---

## 6. Integrasi phpMyAdmin di Laragon

1. Mengunduh paket arsip resmi phpMyAdmin 5.2.x.
2. Mengekstrak ke direktori `C:\laragon\etc\apps\phpMyAdmin`.
3. Mengonfigurasi `config.inc.php` phpMyAdmin untuk mendeteksi port MySQL 8.4 (port default 3306).
4. Integrasi alias Apache pada `C:\laragon\etc\apache2\alias\phpmyadmin.conf`:
   ```apache
   Alias /phpmyadmin "C:/laragon/etc/apps/phpMyAdmin/"
   <Directory "C:/laragon/etc/apps/phpMyAdmin/">
       Options Indexes FollowSymLinks MultiViews
       AllowOverride all
       Require all granted
   </Directory>
   ```
5. Aksesibel langsung di browser via `http://localhost/phpmyadmin`.

---

## 7. Rencana Pengujian & Verifikasi

1. **Uji Lingkungan PHP & Database**:
   * Memastikan ekstensi `intl`, `mbstring`, `gd`, `mysqli`, `fileinfo` terdaftar aktif melalui CLI/script verifikasi.
   * Akses `http://localhost/phpmyadmin` berhasil membuka dashboard MySQL tanpa peringatan dependensi.
2. **Uji Akses Portal Web & Clean URL**:
   * Membuka `http://agrobisnis.test` dan memverifikasi modul rewrite bekerja tanpa error 500 atau 404.
3. **Uji Keamanan & Izin Tulis Direktori**:
   * Melakukan test write/read otomatis pada folder `files_dir/` untuk menjamin Apache memiliki izin simpan naskah submisi.
4. **Uji Multi-Jurnal**:
   * Verifikasi rute portal utama `http://agrobisnis.test/` menampilkan agregasi jurnal.
   * Verifikasi rute spesifik `http://agrobisnis.test/jsep` dan `http://agrobisnis.test/japt` dapat dimuat dengan pengaturan dewan redaksi independen.
