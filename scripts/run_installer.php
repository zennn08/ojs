<?php
require dirname(__DIR__) . '/public_html/tools/bootstrap.php';

class AutoInstaller extends \PKP\cliTool\CommandLineTool {
    public function execute() {
        $params = [
            'locale' => getenv('OJS_LOCALE') ?: 'en',
            'additionalLocales' => ['id'],
            'filesDir' => getenv('OJS_FILES_DIR') ?: (dirname(__DIR__) . '/files_dir'),
            'adminUsername' => getenv('OJS_ADMIN_USER') ?: 'admin',
            'adminPassword' => getenv('OJS_ADMIN_PASSWORD') ?: 'admin12345',
            'adminPassword2' => getenv('OJS_ADMIN_PASSWORD') ?: 'admin12345',
            'adminEmail' => getenv('OJS_ADMIN_EMAIL') ?: 'admin@agrobisnis.test',
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
                file_put_contents(dirname(__DIR__) . '/public_html/config.inc.php', $installer->getConfigContents());
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
