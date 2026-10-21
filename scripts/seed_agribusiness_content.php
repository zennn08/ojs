<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

$mysqli = new mysqli('127.0.0.1', 'root', '', 'ojs_agrobisnis');
if ($mysqli->connect_error) {
    die("FAIL: DB connect error: " . $mysqli->connect_error . "\n");
}

$now = date('Y-m-d H:i:s');
$today = date('Y-m-d');

// Helper to execute prepared queries
function insertRow($mysqli, $table, $data) {
    $keys = array_keys($data);
    $escapedKeys = array_map(fn($k) => "`$k`", $keys);
    $values = array_values($data);
    $placeholders = implode(', ', array_fill(0, count($values), '?'));

    $sql = "INSERT INTO `$table` (" . implode(', ', $escapedKeys) . ") VALUES ($placeholders)";
    $stmt = $mysqli->prepare($sql);
    if (!$stmt) {
        die("Prepare failed for $table: " . $mysqli->error . "\n");
    }

    $types = '';
    foreach ($values as $v) {
        if (is_int($v)) $types .= 'i';
        elseif (is_float($v)) $types .= 'd';
        else $types .= 's';
    }

    $stmt->bind_param($types, ...$values);
    if (!$stmt->execute()) {
        die("Execute failed for $table: " . $stmt->error . "\n");
    }
    $id = $stmt->insert_id;
    $stmt->close();
    return $id;
}

// 1. Clean previous seeded articles/issues if exist
$mysqli->query("SET FOREIGN_KEY_CHECKS = 0");
$mysqli->query("TRUNCATE TABLE issues");
$mysqli->query("TRUNCATE TABLE issue_settings");
$mysqli->query("TRUNCATE TABLE submissions");
$mysqli->query("TRUNCATE TABLE publications");
$mysqli->query("TRUNCATE TABLE publication_settings");
$mysqli->query("TRUNCATE TABLE authors");
$mysqli->query("TRUNCATE TABLE author_settings");
$mysqli->query("TRUNCATE TABLE publication_galleys");
$mysqli->query("SET FOREIGN_KEY_CHECKS = 1");

echo "Cleaned previous issues and submissions.\n";

// 2. Define Issues
$issuesData = [
    [
        'journal_id' => 1,
        'path' => 'jsep',
        'volume' => 1,
        'number' => '1',
        'year' => 2026,
        'url_path' => 'v1n1-2026',
        'title' => 'Vol. 1 No. 1 (2026): Transformasi Sosial Ekonomi & Rantai Pasok Pertanian',
        'desc' => 'Edisi perdana Jurnal Sosial Ekonomi Pertanian memuat riset orisinal seputar kelembagaan agribisnis, efisiensi pemasaran rantai pasok pangan, dan ekonomi sumberdaya pertanian berkelanjutan.'
    ],
    [
        'journal_id' => 2,
        'path' => 'japt',
        'volume' => 1,
        'number' => '1',
        'year' => 2026,
        'url_path' => 'v1n1-2026',
        'title' => 'Vol. 1 No. 1 (2026): Inovasi Presisi & Pascapanen Hasil Pertanian',
        'desc' => 'Edisi perdana Jurnal Agroteknologi dan Pengolahan memuat inovasi agronomi presisi, bioteknologi budidaya, penanganan pascapanen, dan rekayasa produk bernilai tambah.'
    ]
];

$issueIds = [];

foreach ($issuesData as $iss) {
    $issueId = insertRow($mysqli, 'issues', [
        'journal_id' => $iss['journal_id'],
        'volume' => $iss['volume'],
        'number' => $iss['number'],
        'year' => $iss['year'],
        'published' => 1,
        'date_published' => $now,
        'last_modified' => $now,
        'access_status' => 1,
        'show_volume' => 1,
        'show_number' => 1,
        'show_year' => 1,
        'show_title' => 1,
        'url_path' => $iss['url_path']
    ]);

    $issueIds[$iss['path']] = $issueId;

    insertRow($mysqli, 'issue_settings', [
        'issue_id' => $issueId,
        'locale' => 'id',
        'setting_name' => 'title',
        'setting_value' => $iss['title']
    ]);
    insertRow($mysqli, 'issue_settings', [
        'issue_id' => $issueId,
        'locale' => 'en',
        'setting_name' => 'title',
        'setting_value' => $iss['title']
    ]);
    insertRow($mysqli, 'issue_settings', [
        'issue_id' => $issueId,
        'locale' => 'id',
        'setting_name' => 'description',
        'setting_value' => $iss['desc']
    ]);

    // Set current issue on journal
    $mysqli->query("UPDATE journals SET current_issue_id = $issueId WHERE journal_id = {$iss['journal_id']}");
    echo "Created Issue ID $issueId for Journal {$iss['path']}.\n";
}

// 3. Define Articles
$articles = [
    // Journal 1 (JSEP) Articles
    [
        'journal_id' => 1,
        'section_id' => 1,
        'issue_path' => 'jsep',
        'seq' => 1,
        'pages' => '1-14',
        'title_id' => 'Analisis Efisiensi Rantai Pasok Beras Organik Berbasis Kemitraan Petani di Jawa Barat',
        'title_en' => 'Supply Chain Efficiency Analysis of Organic Rice Based on Smallholder Farmer Partnerships in West Java',
        'abstract_id' => 'Penelitian ini mengkaji efisiensi transmisi harga dan elastisitas transmisi rantai pasok beras organik varietas Sintanur di Kabupaten Tasikmalaya dan Cianjur, Jawa Barat. Metode analisis menggunakan Data Envelopment Analysis (DEA) dan Structural Equation Modeling (SEM). Temuan menunjukkan bahwa kemitraan formal antara gabungan kelompok tani (Gapoktan) dan agribisnis integrator mampu memangkas marjin pemasaran hingga 34,2 persen serta meningkatkan pendapatan petani produsen secara signifikan.',
        'abstract_en' => 'This study examines price transmission efficiency and transmission elasticity in the supply chain of Sintanur variety organic rice in Tasikmalaya and Cianjur Regencies, West Java. The analytical framework utilizes Data Envelopment Analysis (DEA) and Structural Equation Modeling (SEM). Findings indicate that formal contract farming partnerships between farmer group federations (Gapoktan) and agribusiness integrators reduced marketing margins by 34.2 percent and significantly increased smallholder farm household income.',
        'authors' => [
            ['given' => 'Bambang', 'family' => 'Suryono', 'email' => 'bambang.suryono@ipb.ac.id', 'affil' => 'Departemen Agribisnis, Fakultas Ekonomi dan Manajemen, IPB University, Bogor, Indonesia'],
            ['given' => 'Ratna', 'family' => 'Wulandari', 'email' => 'ratna.wulandari@ipb.ac.id', 'affil' => 'Pusat Studi Pembangunan Pertanian dan Pedesaan (PSP3), IPB University, Bogor, Indonesia']
        ]
    ],
    [
        'journal_id' => 1,
        'section_id' => 1,
        'issue_path' => 'jsep',
        'seq' => 2,
        'pages' => '15-28',
        'title_id' => 'Dinamika Kelembagaan Kelompok Tani dan Pengaruhnya terhadap Pendapatan Petani Kopi Rakyat',
        'title_en' => 'Institutional Dynamics of Farmer Groups and Their Effect on Smallholder Coffee Farmers Income',
        'abstract_id' => 'Sektor perkebunan kopi rakyat di Temanggung menghadapi tantangan fluktuasi harga global dan asimetri informasi pasar. Studi ini menganalisis modal sosial kelembagaan kelompok tani dalam pengadaan sarana produksi bersama dan pemasaran biji kopi arabika. Hasil uji empiris membuktikan bahwa transparansi tata kelola kelompok tani berkorelasi positif kuat terhadap daya tawar petani di pasar lelang lokal.',
        'abstract_en' => 'The smallholder coffee sector in Temanggung faces persistent challenges from global price volatility and market information asymmetry. This study investigates the institutional social capital of farmer groups in joint input procurement and specialty arabica green bean marketing. Empirical findings confirm that organizational transparency within farmer collectives has a strong positive correlation with local auction bargaining power.',
        'authors' => [
            ['given' => 'Hendra', 'family' => 'Gunawan', 'email' => 'hendra.gunawan@ugm.ac.id', 'affil' => 'Program Studi Ekonomi Pertanian, Fakultas Pertanian, Universitas Gadjah Mada, Yogyakarta, Indonesia'],
            ['given' => 'Tri', 'family' => 'Wahyudi', 'email' => 'tri.wahyudi@ugm.ac.id', 'affil' => 'Magister Manajemen Agribisnis, Universitas Gadjah Mada, Yogyakarta, Indonesia']
        ]
    ],
    [
        'journal_id' => 1,
        'section_id' => 1,
        'issue_path' => 'jsep',
        'seq' => 3,
        'pages' => '29-42',
        'title_id' => 'Strategi Pemasaran Komoditas Bawang Merah pada Era Digital di Sentra Produksi Brebes',
        'title_en' => 'Marketing Strategies for Shallot Commodities in the Digital Era in the Brebes Production Center',
        'abstract_id' => 'Bawang merah merupakan komoditas strategis hortikultura dengan volatilitas harga musiman yang ekstrem. Riset ini mengeksplorasi adopsi platform agritech terpadu oleh petani milenial di sentra Brebes. Integrasi lelang digital dan manajemen gudang berpendingin (cold storage) berhasil meredam penurunan harga saat panen raya sebesar 28 persen.',
        'abstract_en' => 'Shallot is a strategic horticultural crop subject to severe seasonal price fluctuations. This research explores the adoption of integrated agritech digital platforms by young farmers in the Brebes production hub. Integrating digital auction platforms with controlled-atmosphere cold storage effectively mitigated price collapse during peak harvests by 28 percent.',
        'authors' => [
            ['given' => 'Arif', 'family' => 'Budiman', 'email' => 'arif.budiman@unpad.ac.id', 'affil' => 'Departemen Sosial Ekonomi Pertanian, Fakultas Pertanian, Universitas Padjadjaran, Bandung, Indonesia'],
            ['given' => 'Siti', 'family' => 'Nurhaliza', 'email' => 'siti.nurhaliza@unpad.ac.id', 'affil' => 'Pusat Riset Rantai Pasok Pangan, Universitas Padjadjaran, Sumedang, Indonesia']
        ]
    ],

    // Journal 2 (JAPT) Articles
    [
        'journal_id' => 2,
        'section_id' => 2,
        'issue_path' => 'japt',
        'seq' => 1,
        'pages' => '1-16',
        'title_id' => 'Optimasi Pemupukan Presisi Berbasis Sensor NPK untuk Peningkatan Produktivitas Kelapa Sawit',
        'title_en' => 'NPK Sensor Based Precision Fertilization Optimization for Enhancing Oil Palm Productivity',
        'abstract_id' => 'Efisiensi serapan hara makro pada perkebunan kelapa sawit tanah mineral sangat menentukan formasi tandan buah segar (TBS). Penelitian lapangan menguji algoritma pemupukan presisi berbasis integrasi sensor NPK IoT dan citra drone multispektral. Hasil demonstrasi plot selama 12 bulan membuktikan efisiensi pupuk kimia sebesar 21,5 persen tanpa menurunkan rendemen CPO.',
        'abstract_en' => 'Macronutrient uptake efficiency in mineral soil oil palm plantations directly governs fresh fruit bunch (FFB) synthesis. This field study evaluates a precision fertilization algorithm integrating IoT soil NPK sensor networks and multispectral drone imagery. Longitudinal plot data over 12 months demonstrated a 21.5 percent reduction in chemical fertilizer application with no compromise to crude palm oil extraction rates.',
        'authors' => [
            ['given' => 'Agus', 'family' => 'Pramono', 'email' => 'agus.pramono@instiperjogja.ac.id', 'affil' => 'Pusat Riset Agroindustri dan Kelapa Sawit, Institut Pertanian Stiper, Yogyakarta, Indonesia'],
            ['given' => 'Dwi', 'family' => 'Haryanto', 'email' => 'dwi.haryanto@instiperjogja.ac.id', 'affil' => 'Fakultas Pertanian, Institut Pertanian Stiper, Yogyakarta, Indonesia']
        ]
    ],
    [
        'journal_id' => 2,
        'section_id' => 2,
        'issue_path' => 'japt',
        'seq' => 2,
        'pages' => '17-30',
        'title_id' => 'Pengaruh Suhu Pengeringan terhadap Mutu Fisikokimia Bubuk Kakao Fermentasi',
        'title_en' => 'Effect of Drying Temperature on Physicochemical Quality of Fermented Cocoa Powder',
        'abstract_id' => 'Karakteristik aroma dan retensi antioksidan polifenol pada bubuk kakao sangat dipengaruhi oleh kinetika dehidrasi pascapanen. Kajian eksperimental menggunakan fluidized bed dryer pada rentang suhu 50-70 derajat Celsius. Suhu pengeringan konstan 60 derajat Celsius menghasilkan profil asam lemak bebas paling stabil dan kadar lemak seimbang.',
        'abstract_en' => 'Aroma profile characteristics and polyphenol antioxidant retention in cocoa powder are strictly governed by postharvest dehydration kinetics. This experimental trial utilized a fluidized bed dryer across a 50-70 degrees Celsius range. A constant drying temperature of 60 degrees Celsius yielded the optimal balance of low free fatty acids and stable fat distribution.',
        'authors' => [
            ['given' => 'Muhammad', 'family' => 'Ilham', 'email' => 'm.ilham@unhas.ac.id', 'affil' => 'Program Studi Ilmu dan Teknologi Pangan, Universitas Hasanuddin, Makassar, Indonesia'],
            ['given' => 'Nurul', 'family' => 'Fadhilah', 'email' => 'nurul.fadhilah@unhas.ac.id', 'affil' => 'Fakultas Pertanian, Universitas Hasanuddin, Makassar, Indonesia']
        ]
    ],
    [
        'journal_id' => 2,
        'section_id' => 2,
        'issue_path' => 'japt',
        'seq' => 3,
        'pages' => '31-45',
        'title_id' => 'Pemanfaatan Limbah Kulit Singkong sebagai Bahan Baku Bio-Plastik Ramah Lingkungan',
        'title_en' => 'Utilization of Cassava Peel Waste as Sustainable Raw Material for Biodegradable Bioplastics',
        'abstract_id' => 'Industri tapioka menghasilkan limbah padat kulit singkong dalam volume melimpah yang berpotensi mencemari ekosistem perairan. Riset ini merekayasa ekstraksi pati kulit singkong dengan penambahan plasticizer gliserol dan kitosan limbah udang. Bio-plastik yang dihasilkan memiliki kekuatan tarik 14,8 MPa dan terurai sempurna di tanah dalam 28 hari.',
        'abstract_en' => 'The tapioca processing agro-industry produces massive volumes of cassava peel solid waste which endangers rural aquatic habitats. This study synthesizes bioplastic films from cassava peel starch reinforced with glycerol plasticizer and crustacean waste chitosan. The resulting bioplastic demonstrated tensile strength of 14.8 MPa and achieved complete soil biodegradation within 28 days.',
        'authors' => [
            ['given' => 'Retno', 'family' => 'Kusumaningrum', 'email' => 'retno.kusuma@ub.ac.id', 'affil' => 'Jurusan Teknologi Industri Pertanian, Fakultas Teknologi Pertanian, Universitas Brawijaya, Malang, Indonesia'],
            ['given' => 'Eko', 'family' => 'Prasetyo', 'email' => 'eko.prasetyo@ub.ac.id', 'affil' => 'Laboratorium Biopolimer Agroindustri, Universitas Brawijaya, Malang, Indonesia']
        ]
    ]
];

foreach ($articles as $art) {
    $issueId = $issueIds[$art['issue_path']];

    // 1. Insert Submission
    $subId = insertRow($mysqli, 'submissions', [
        'context_id' => $art['journal_id'],
        'stage_id' => 5, // STAGE_ID_PRODUCTION
        'status' => 3,   // STATUS_PUBLISHED
        'submission_progress' => '',
        'locale' => 'id',
        'date_submitted' => '2026-04-10 14:00:00',
        'date_last_activity' => $now,
        'last_modified' => $now
    ]);

    // 2. Insert Publication
    $pubId = insertRow($mysqli, 'publications', [
        'submission_id' => $subId,
        'status' => 3, // STATUS_PUBLISHED
        'version' => 1,
        'section_id' => $art['section_id'],
        'issue_id' => $issueId,
        'seq' => $art['seq'],
        'access_status' => 0,
        'date_published' => $today,
        'last_modified' => $now
    ]);

    // Link current publication
    $mysqli->query("UPDATE submissions SET current_publication_id = $pubId WHERE submission_id = $subId");

    // 3. Insert Publication Settings
    $settings = [
        ['locale' => 'id', 'name' => 'title', 'value' => $art['title_id']],
        ['locale' => 'en', 'name' => 'title', 'value' => $art['title_en']],
        ['locale' => 'id', 'name' => 'abstract', 'value' => $art['abstract_id']],
        ['locale' => 'en', 'name' => 'abstract', 'value' => $art['abstract_en']],
        ['locale' => '', 'name' => 'pages', 'value' => $art['pages']],
        ['locale' => '', 'name' => 'copyrightYear', 'value' => '2026']
    ];

    foreach ($settings as $s) {
        insertRow($mysqli, 'publication_settings', [
            'publication_id' => $pubId,
            'locale' => $s['locale'],
            'setting_name' => $s['name'],
            'setting_value' => $s['value']
        ]);
    }

    // 4. Insert Authors
    $userGroupId = ($art['journal_id'] === 1) ? 14 : 32;
    $authSeq = 1;
    $primaryContactId = null;

    foreach ($art['authors'] as $auth) {
        $authorId = insertRow($mysqli, 'authors', [
            'publication_id' => $pubId,
            'email' => $auth['email'],
            'seq' => $authSeq,
            'user_group_id' => $userGroupId,
            'include_in_browse' => 1
        ]);

        if ($authSeq === 1) {
            $primaryContactId = $authorId;
        }

        // Author settings
        insertRow($mysqli, 'author_settings', [
            'author_id' => $authorId,
            'locale' => 'id',
            'setting_name' => 'givenName',
            'setting_value' => $auth['given']
        ]);
        insertRow($mysqli, 'author_settings', [
            'author_id' => $authorId,
            'locale' => 'id',
            'setting_name' => 'familyName',
            'setting_value' => $auth['family']
        ]);
        insertRow($mysqli, 'author_settings', [
            'author_id' => $authorId,
            'locale' => 'en',
            'setting_name' => 'givenName',
            'setting_value' => $auth['given']
        ]);
        insertRow($mysqli, 'author_settings', [
            'author_id' => $authorId,
            'locale' => 'en',
            'setting_name' => 'familyName',
            'setting_value' => $auth['family']
        ]);
        insertRow($mysqli, 'author_settings', [
            'author_id' => $authorId,
            'locale' => 'id',
            'setting_name' => 'affiliation',
            'setting_value' => $auth['affil']
        ]);
        insertRow($mysqli, 'author_settings', [
            'author_id' => $authorId,
            'locale' => 'en',
            'setting_name' => 'affiliation',
            'setting_value' => $auth['affil']
        ]);
        insertRow($mysqli, 'author_settings', [
            'author_id' => $authorId,
            'locale' => '',
            'setting_name' => 'country',
            'setting_value' => 'ID'
        ]);

        $authSeq++;
    }

    // Set primary contact
    if ($primaryContactId) {
        $mysqli->query("UPDATE publications SET primary_contact_id = $primaryContactId WHERE publication_id = $pubId");
    }

    // 5. Insert Galley Link (PDF)
    insertRow($mysqli, 'publication_galleys', [
        'publication_id' => $pubId,
        'label' => 'PDF',
        'locale' => 'id',
        'seq' => 1,
        'is_approved' => 1
    ]);

    echo "Seeded article: '{$art['title_id']}' (Sub ID $subId, Pub ID $pubId)\n";
}

echo "All 6 agribusiness research articles seeded successfully.\n";
