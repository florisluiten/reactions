<?php
/**
 * Autoloader - The autoloader for retreiving all PHP sources
 *
 * @package Reactions
 * @author  Floris Luiten <floris@florisluiten.nl>
 */

declare(strict_types=1);

spl_autoload_register(function ($class) {
    if (substr($class, 0, 18) !== 'Fluiten\\Reactions\\') {
        return false;
    }

    return include APP_DIR . str_replace('\\', '/', substr($class, 18)) . '.php';
});
