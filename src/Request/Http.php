<?php
/**
 * HTTP request
 *
 * @author  Floris Luiten <floris@florisluiten.nl>
 * @package Reactions
 */

declare(strict_types=1);

namespace Fluiten\Reactions\Request;

class Http
{
    /**
     * @var string[] The server array
     */
    private $server;

    /**
     * Constructor
     *
     * @param string[] $server The server array, eg $_SERVER
     *
     * @return void
     */
    public function __construct(array $server)
    {
        $this->server = $server;
    }

    /**
     * Returns the path of the request, eg '/1234/filename'
     *
     * @return string
     */
    public function getPath()
    {
        return parse_url($this->server['REQUEST_URI'], \PHP_URL_PATH);
    }
}
