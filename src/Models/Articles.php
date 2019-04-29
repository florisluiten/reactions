<?php
/**
 * Articles model
 *
 * @author  Floris Luiten <floris@florisluiten.nl>
 * @package Reactions
 */

declare(strict_types=1);

namespace Fluiten\Reactions\Models;

use \Fluiten\Reactions as App;

class Articles
{
    /**
     * @param string The title of the article
     */
    public $title;

    /**
     * @param string The content of the article
     */
    public $content;

    /**
     * Handle the HTTP request and return the response
     *
     * @param \Fluiten\Reactions\Request\Http $request The HTTP request
     * @param string                          $ID      The article ID
     *
     * @return string
     */
    public function queryById(\PDO $database, string $ID): ? \PDOStatement
    {
        $statement = $database->prepare('SELECT `articleID`, `content`, `title` FROM `articles` WHERE `articleID` = :ID');
        $statement->bindParam(':ID', $ID);

        $statement->setFetchMode(\PDO::FETCH_CLASS, __CLASS__);

        return $statement;
    }
}
