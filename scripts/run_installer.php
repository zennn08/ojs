<?php
require 'D:/Project/agrobisnis-ojs/public_html/tools/bootstrap.php';

class AutoInstaller extends \PKP\cliTool\CommandLineTool {
    public function execute() {
        $params = [
            'locale' => 'en',
            'additionalLocales' => ['id'],
            'filesDir' => 'D:/Project/agrobisnis-ojs/files_dir',
            'adminUsername' => 'admin',
            'adminPassword' => 'admin12345',
            'adminPassword2' => 'admin12345',
            'adminEmail' => 'admin@agrobisnis.test',
            'databaseDriver' => 'mysqli',
            'databaseHost' => '127.0.0.1',
            'databaseUsername' => 'root',
            'databasePassword' => '',
            'databaseName' => 'ojs_agrobisnis',
            'connectionCharset' => 'utf8mb4',
            'databaseCharset' => 'utf8mb4',
            'oaiRepositoryId' => 'agrobisnis.test',
            'enableBeacon' => false,
            'install' => true,
        ];
        $installer = new \APP\install\Install($params);
        if ($installer->execute()) {
            echo "INSTALL SUCCESS\n";
            if (!$installer->wroteConfig()) {
                file_put_contents('D:/Project/agrobisnis-ojs/public_html/config.inc.php', $installer->getConfigContents());
                echo "WROTE CONFIG MANUALLY\n";
            } else {
                echo "CONFIG WRITTEN AUTOMATICALLY\n";
            }
        } else {
            echo "INSTALL FAILED: " . implode(', ', $installer->getErrorDetails() ?? $installer->getNotes()) . "\n";
        }
    }
}

$tool = new AutoInstaller($argv ?? []);
$tool->execute();
