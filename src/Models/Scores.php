<?php
/**
 * Scores model
 *
 * @package Reactions
 * @author  Floris Luiten <floris@florisluiten.nl>
 */

declare(strict_types=1);

namespace Fluiten\Reactions\Models;

use \Fluiten\Reactions as App;

class Scores
{
    /**
     * @var string The reactionID
     */
    public $reactionID;

    /**
     * @var string The score, rounded
     */
    public $score;

    /**
     * @var string The userID
     */
    public $userID;

    /**
     * Add a new score
     *
     * @param \PDO              $database The database connection
     * @param App\Models\Scores $score    The score
     *
     * @return boolean True on success, false otherwise
     */
    public static function add(\PDO $database, App\Models\Scores $score)
    {
        $statement = $database->prepare(
            "REPLACE INTO `reactionScores` (`reactionID`, `userID`, `score`) VALUES(:REACTIONID, :USERID, :SCORE)"
        );

        $statement->bindParam(':REACTIONID', $score->reactionID);
        $statement->bindParam(':USERID', $score->userID);
        $statement->bindParam(':SCORE', $score->score);

        return $statement->execute();
    }
}
