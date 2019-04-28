<?php
/**
 * Autoloader - The autoloader for retreiving all PHP sources
 *
 * @author  Floris Luiten <floris@florisluiten.nl>
 * @package Reactions
 */

declare(strict_types=1);

spl_autoload_register(function ($class) {
    if (substr($class, 0, 8) !== 'Fluiten\\') {
        return false;
    }

    return include APP_DIR . str_replace('\\', '/', $class) . '.php';
});
