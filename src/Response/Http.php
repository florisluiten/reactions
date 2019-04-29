<?php
/**
 * HTTP response
 *
 * @author  Floris Luiten <floris@florisluiten.nl>
 * @package Reactions
 */

declare(strict_types=1);

namespace Fluiten\Reactions\Response;

use \Fluiten\Reactions as App;

class Http
{
    /**
     * Handle the HTTP request and return the response
     *
     * @param \Fluiten\Reactions\Request\Http $request The HTTP request
     *
     * @return string
     */
    public function handleRequest(\Fluiten\Reactions\Request\Http $request): string
    {
        return $this->parseView('articles-index');
    }

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
