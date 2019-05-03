<?php
/**
 * Response base
 *
 * @package Reactions
 * @author  Floris Luiten <floris@florisluiten.nl>
 */

declare(strict_types=1);

namespace Fluiten\Reactions\Response;

use \Fluiten\Reactions as App;

abstract class Base
{
    /**
     * @var \PDO $database The current database connection
     */
    protected $database;

    /**
     * Constructor
     *
     * @param \PDO $database The database
     *
     * @return string
     */
    public function __construct(\PDO $database)
    {
        $this->database = $database;
    }

    /**
     * Parse and return the view
     *
     * @param string  $view The name of the view, eg 'index'
     * @param mixed[] $data The data to pass the view
     *
     * @return string
     */
    protected function parseView(string $view, $data = array()): string
    {
        foreach ($data as $key => $value) {
            $$key = $value;
        }

        ob_start();
        include APP_DIR . 'Views/' . $view . '.php';
        return ob_get_clean();
    }
}
