<?php
/**
 * Reactions model
 *
 * @author  Floris Luiten <floris@florisluiten.nl>
 * @package Reactions
 */

declare(strict_types=1);

namespace Fluiten\Reactions\Models;

use \Fluiten\Reactions as App;

class Reactions
{
    /**
     * @param string The reactionID
     */
    public $reactionID;

    /**
     * @param string The parentID
     */
    public $parentID;

    /**
     * @param string The articleID
     */
    public $articleID;

    /**
     * @param string The score
     */
    public $score;

    /**
     * @param string The userID
     */
    public $userID;

    /**
     * @param string The publish date
     */
    public $publishDate;

    /**
     * @param string The content
     */
    public $content;

    /**
     * Return the query for getting the reactions
     *
     * @param \PDO   $database The database handle
     * @param string $ID       The article ID
     *
     * @return \PDOStatement
     */
    public function queryByArticle(\PDO $database, string $articleID): ? \PDOStatement
    {
        $statement = $database->prepare(
            'SELECT `reactionID`, `parentID`, `articleID`, `score`, `userID`, `publishDate`, `content` '
            . 'FROM `reactions` '
            . 'WHERE articleID = :ID'
        );
        $statement->bindParam(':ID', $articleID);

        $statement->setFetchMode(\PDO::FETCH_CLASS, __CLASS__);

        return $statement;
    }

    /**
     * Return the reactions in a tree layout
     *
     * @param \PDO   $database The database handle
     * @param string $ID       The article ID
     *
     * @return \PDOStatement
     */
    public function getThread(\PDO $database, string $articleID): array
    {
        $reactions = array();

        $statement = self::queryByArticle($database, $articleID);
        $statement->execute();

        while ($row = $statement->fetch(\PDO::FETCH_ASSOC)) {
            $reactions[] = array_merge(
                $row,
                array(
                    'ID' => $row['reactionID']
                )
            );
        }

        return App\arrayToTree($reactions);
    }
}
