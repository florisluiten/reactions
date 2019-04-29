<?php
/**
 * Response base
 *
 * @author  Floris Luiten <floris@florisluiten.nl>
 * @package Reactions
 */

declare(strict_types=1);

namespace Fluiten\Reactions\Response;

use \Fluiten\Reactions as App;

abstract class Base
{
    /**
     * Parse and return the view
     *
     * @param string  $view The name of the view, eg 'index'
     * @param mixed[] $data The data to pass the view
     *
     * @return string
     */
    private function parseView(string $view, $data = array()): string
    {
        foreach ($data as $key => $value) {
            $$key = $value;
        }

        ob_start();
        include APP_DIR . 'Views/' . $view . '.php';
        return ob_get_clean();
    }
}
