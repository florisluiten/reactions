<?php
/**
 * Articles model
 *
 * @package Reactions
 * @author  Floris Luiten <floris@florisluiten.nl>
 */

declare(strict_types=1);

namespace Fluiten\Reactions\Models;

use \Fluiten\Reactions as App;

class Articles
{
    /**
     * @var string The title of the article
     */
    public $title;

    /**
     * @var string The content of the article
     */
    public $content;

    /**
     * Return the query for getting the articles
     *
     * @param \PDO   $database The database handle
     * @param string $ID       The article ID
     *
     * @return \PDOStatement
     */
    public function queryById(\PDO $database, string $ID): ? \PDOStatement
    {
        $statement = $database->prepare(
            'SELECT `articleID`, `content`, `title` FROM `articles` WHERE `articleID` = :ID'
        );
        $statement->bindParam(':ID', $ID);

        $statement->setFetchMode(\PDO::FETCH_CLASS, __CLASS__);

        return $statement;
    }
}
