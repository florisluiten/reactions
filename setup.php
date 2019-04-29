<?php
/**
 * Setup the application
 *
 * @author  Floris Luiten <floris@florisluiten.nl>
 * @package Reactions
 */

declare(strict_types=1);

require_once dirname(__FILE__) . '/defines.php';
require_once APP_DIR . 'Autoload.php';

$currentPath = dirname(__FILE__) . '/';

if (!is_file($currentPath. 'env.php')) {
    printf('Your environment is not set yet. Please copy %senv.example.php to %senv.php, and adjust the file to your '
    . 'environment. For more information, please see %sINSTALLATION.md' . PHP_EOL, $currentPath, $currentPath, $currentPath);
    exit(2);
}

$environment = require $currentPath . 'env.php';

try {
    $database = new \PDO($environment['PDO']['DSN'], $environment['PDO']['username'], $environment['PDO']['password']);
} catch (\PDOException $e) {
    printf('Cannot connect to the database: %s' . PHP_EOL
    . 'Please look at your %senv.php file if the configuration is correct.' . PHP_EOL, $e->getMessage(), $currentPath);
    exit(3);
} catch (\Throwable $e) {
    printf('Cannot connect to the database, configuration file looks incorrect: %s' . PHP_EOL
    . 'Please look at your %senv.php file if the configuration is correct.' . PHP_EOL, $e->getMessage(), $currentPath);
    exit(3);
}
