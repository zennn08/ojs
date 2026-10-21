<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require 'D:/Project/agrobisnis-ojs/public_html/tools/bootstrap.php';

use APP\core\Application;

class MultiJournalSetup extends \PKP\cliTool\CommandLineTool {
    public function execute() {
        $contextService = app()->get('context');
        $journalDao = Application::getContextDAO();
        $request = Application::get()->getRequest();

        // 1. Update Site Settings
        $site = $request->getSite();
        $siteDao = \PKP\db\DAORegistry::getDAO('SiteDAO');
        $site->setData('title', 'Portal Jurnal Agribisnis Indonesia', 'en');
        $site->setData('title', 'Portal Jurnal Agribisnis Indonesia', 'id');
        $site->setData('intro', 'Selamat datang di Portal Multi-Jurnal Ilmiah Agribisnis Indonesia. Menghadirkan publikasi riset berkualitas tinggi di bidang sosial ekonomi pertanian, agroteknologi, dan manajemen agribisnis.', 'id');
        $site->setData('intro', 'Welcome to the Indonesian Agribusiness Scientific Multi-Journal Portal. Featuring high quality research in agricultural socio-economics, agrotechnology, and agribusiness management.', 'en');
        $site->setData('showDescription', 1);
        $siteDao->updateObject($site);
        echo "PASS: Site branding configured.\n";

        // 2. Define Pilot Journals
        $journalsToCreate = [
            [
                'urlPath' => 'jsep',
                'name' => [
                    'id' => 'Jurnal Sosial Ekonomi Pertanian',
                    'en' => 'Journal of Agricultural Socio-Economics'
                ],
                'acronym' => [
                    'id' => 'JSEP',
                    'en' => 'JSEP'
                ],
                'description' => [
                    'id' => 'Fokus pada riset agribisnis pedesaan, kelembagaan petani, rantai pasok pangan, dan ekonomi sumberdaya pertanian.',
                    'en' => 'Focusing on rural agribusiness research, farmer institutions, food supply chains, and agricultural resource economics.'
                ],
                'primaryLocale' => 'id',
                'supportedLocales' => ['id', 'en'],
                'enabled' => 1,
                'seq' => 1,
            ],
            [
                'urlPath' => 'japt',
                'name' => [
                    'id' => 'Jurnal Agroteknologi dan Pengolahan Hasil Pertanian',
                    'en' => 'Journal of Agrotechnology and Agro-Industrial Processing'
                ],
                'acronym' => [
                    'id' => 'JAPT',
                    'en' => 'JAPT'
                ],
                'description' => [
                    'id' => 'Fokus pada presisi agronomi, teknologi budidaya berkelanjutan, penanganan pascapanen, dan inovasi pengolahan produk hasil tani.',
                    'en' => 'Focusing on precision agronomy, sustainable cultivation technology, postharvest handling, and processed crop product innovations.'
                ],
                'primaryLocale' => 'id',
                'supportedLocales' => ['id', 'en'],
                'enabled' => 1,
                'seq' => 2,
            ]
        ];

        foreach ($journalsToCreate as $jData) {
            $existing = $journalDao->getByPath($jData['urlPath']);
            if ($existing) {
                echo "INFO: Journal '{$jData['urlPath']}' already exists (ID: {$existing->getId()}). Updating...\n";
                $journal = $existing;
            } else {
                $journal = $journalDao->newDataObject();
            }

            $journal->setData('urlPath', $jData['urlPath']);
            $journal->setData('primaryLocale', $jData['primaryLocale']);
            $journal->setData('supportedLocales', $jData['supportedLocales']);
            $journal->setData('supportedFormLocales', $jData['supportedLocales']);
            $journal->setData('supportedSubmissionLocales', $jData['supportedLocales']);
            $journal->setData('enabled', $jData['enabled']);
            $journal->setData('seq', $jData['seq']);

            foreach ($jData['name'] as $loc => $val) {
                $journal->setData('name', $val, $loc);
            }
            foreach ($jData['acronym'] as $loc => $val) {
                $journal->setData('acronym', $val, $loc);
            }
            foreach ($jData['description'] as $loc => $val) {
                $journal->setData('description', $val, $loc);
            }

            if ($existing) {
                $journalDao->updateObject($journal);
                echo "PASS: Journal '{$jData['urlPath']}' updated.\n";
            } else {
                $contextService->add($journal, $request);
                echo "PASS: Journal '{$jData['urlPath']}' created successfully.\n";
            }
        }
    }
}

(new MultiJournalSetup([]))->execute();
