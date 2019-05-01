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
            'SELECT `reactions`.`reactionID`, `reactions`.`parentID`, `reactions`.`articleID`, `reactions`.`score`, '
            . '`reactions`.`userID`, `reactions`.`publishDate`, `reactions`.`content`, '
            . '`users`.`name` as username, `users`.`image` as userimage '
            . 'FROM `reactions` '
            . 'LEFT JOIN `users` ON `users`.`userID` = `reactions`.`userID` '
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

    /**
     * Add a new comment
     *
     * @param \PDO $database The database connection
     * @param App\Models\Reaction $reaction The reaction
     *
     * @return boolean True on success, false otherwise
     */
    public function add(\PDO $database, App\Models\Reactions $reaction)
    {
        $now = (new \Datetime())->format('Y-m-d H:i:s');

        $statement = $database->prepare(
            "INSERT INTO `reactions` (`parentID`, `articleID`, `score`, `userID`, `publishDate`, `content`)"
            . " VALUES(:PARENTID, :ARTICLEID, 0, :USERID, :NOW, :CONTENT)"
        );

        $statement->bindParam(':PARENTID', $reaction->parentID);
        $statement->bindParam(':ARTICLEID', $reaction->articleID);
        $statement->bindParam(':USERID', $reaction->userID);
        $statement->bindParam(':NOW', $now);
        $statement->bindParam(':CONTENT', $reaction->content);

        return $statement->execute();
    }
}
