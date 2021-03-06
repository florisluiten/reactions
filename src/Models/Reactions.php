<?php
/**
 * Reactions model
 *
 * @package Reactions
 * @author  Floris Luiten <floris@florisluiten.nl>
 */

declare(strict_types=1);

namespace Fluiten\Reactions\Models;

use \Fluiten\Reactions as App;

class Reactions
{
    /**
     * @var string The reactionID
     */
    public $reactionID;

    /**
     * @var string The parentID
     */
    public $parentID;

    /**
     * @var string The articleID
     */
    public $articleID;

    /**
     * @var string The score
     */
    public $score;

    /**
     * @var string The userID
     */
    public $userID;

    /**
     * @var string The publish date
     */
    public $publishDate;

    /**
     * @var string The content
     */
    public $content;

    /**
     * Return the query for getting the reactions
     *
     * @param \PDO    $database  The database handle
     * @param string  $articleID The article ID
     * @param integer $sort      Indicate the sorting, defaults to
     *                           SORT_OLDEST_FIRST
     *
     * @return \PDOStatement
     */
    public static function queryByArticle(\PDO $database, string $articleID, int $sort = \SORT_OLDEST_FIRST): ? \PDOStatement
    {
        $query = 'SELECT `reactions`.`reactionID`, `reactions`.`parentID`, `reactions`.`articleID`, '
            . '(SELECT IFNULL(ROUND(AVG(`score`), 0), 0) '
            . ' FROM `reactionScores` '
            . ' WHERE reactions.reactionID = reactionScores.reactionID'
            . ') as score, '
            . '`reactions`.`userID`, `reactions`.`publishDate`, `reactions`.`content`, '
            . '`users`.`name` as username, `users`.`image` as userimage '
            . 'FROM `reactions` '
            . 'LEFT JOIN `users` ON `users`.`userID` = `reactions`.`userID` '
            . 'WHERE articleID = :ID ORDER BY `publishDate` ';

        if ($sort == \SORT_OLDEST_FIRST) {
            $query .= 'ASC';
        } else {
            $query .= 'DESC';
        }

        $statement = $database->prepare($query);

        $statement->bindParam(':ID', $articleID);

        $statement->setFetchMode(\PDO::FETCH_CLASS, __CLASS__);

        return $statement;
    }

    /**
     * Return the reactions in a tree layout
     *
     * @param \PDO    $database  The database handle
     * @param string  $articleID The article ID
     * @param integer $sort      Indicate the sorting, defaults to
     *                           SORT_OLDEST_FIRST
     *
     * @return \PDOStatement
     */
    public static function getThread(\PDO $database, string $articleID, int $sort = \SORT_OLDEST_FIRST): array
    {
        $reactions = array();

        $statement = self::queryByArticle($database, $articleID, $sort);
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
     * @param \PDO                 $database The database connection
     * @param App\Models\Reactions $reaction The reaction
     *
     * @return boolean True on success, false otherwise
     */
    public static function add(\PDO $database, App\Models\Reactions $reaction)
    {
        $now = (new \Datetime())->format('Y-m-d H:i:s');

        $statement = $database->prepare(
            "INSERT INTO `reactions` (`parentID`, `articleID`, `userID`, `publishDate`, `content`)"
            . " VALUES(:PARENTID, :ARTICLEID, :USERID, :NOW, :CONTENT)"
        );

        $statement->bindParam(':PARENTID', $reaction->parentID);
        $statement->bindParam(':ARTICLEID', $reaction->articleID);
        $statement->bindParam(':USERID', $reaction->userID);
        $statement->bindParam(':NOW', $now);
        $statement->bindParam(':CONTENT', $reaction->content);

        return $statement->execute();
    }

    /**
     * Get a specific reaction
     *
     * @param \PDO   $database   The database connection
     * @param string $reactionID The reactionID
     *
     * @return App\Models\Reaction The reaction, or null
     */
    public static function getById(\PDO $database, string $reactionID): ? App\Models\Reactions
    {
        $statement = $database->prepare(
            'SELECT `reactions`.`reactionID`, `reactions`.`parentID`, `reactions`.`articleID`, '
            . '(SELECT IFNULL(ROUND(AVG(`score`), 0), 0) '
            . ' FROM `reactionScores` '
            . ' WHERE reactions.reactionID = reactionScores.reactionID'
            . ') as score, '
            . '`reactions`.`userID`, `reactions`.`publishDate`, `reactions`.`content`, '
            . '`users`.`name` as username, `users`.`image` as userimage, `users`.`userID` as userID '
            . 'FROM `reactions` '
            . 'LEFT JOIN `users` ON `users`.`userID` = `reactions`.`userID` '
            . 'WHERE reactionID = :REACTIONID'
        );
        $statement->bindParam(':REACTIONID', $reactionID);

        $statement->setFetchMode(\PDO::FETCH_CLASS, __CLASS__);
        $statement->execute();

        $result = $statement->fetch();

        return ($result === false) ? null : $result;
    }
}
