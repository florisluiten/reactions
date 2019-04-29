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

class Http extends Base
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
}
