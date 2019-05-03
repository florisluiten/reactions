<?php
/**
 * Test for Models\Scores
 *
 * @package    Reactions
 * @author     Floris Luiten <floris@florisluiten.nl>
 */

declare(strict_types=1);

namespace Fluiten\Reactions\Tests;

use \Fluiten\Reactions as App;

class ModelsScoresTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Setup for each test
     *
     * @return void
     */
    public function setUp(): void
    {
        $this->database = new \PDO('sqlite::memory:');
        $this->database->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        $this->database->query(
            "CREATE TABLE `articles` (`articleID` INTEGER PRIMARY KEY AUTOINCREMENT, "
            . "`title` VARCHAR(100) NOT NULL, `content` MEDIUMTEXT NOT NULL)"
        );

        $this->database->query(
            "CREATE TABLE `users` ( `userID` INTEGER  PRIMARY KEY AUTOINCREMENT, "
            . "`name` VARCHAR(100) NOT NULL, "
            . "`image` VARCHAR(100) NULL) "
        );

        $this->database->query(
            "CREATE TABLE `reactionScores` ( `reactionID` BIGINT UNSIGNED NOT NULL, "
            . "`userID` BIGINT UNSIGNED NOT NULL, "
            . "`score` TINYINT NOT NULL, "
            . "PRIMARY KEY (`reactionID`, `userID`), "
            . "FOREIGN KEY (`reactionID`) REFERENCES `reactions` (`reactionID`),"
            . "FOREIGN KEY (`userID`) REFERENCES `users` (`userID`))"
        );

        $this->database->query(
            "CREATE TABLE `reactions` ( `reactionID` INTEGER PRIMARY KEY AUTOINCREMENT, "
            . "`parentID` BIGINT UNSIGNED NULL DEFAULT NULL, "
            . "`articleID` BIGINT UNSIGNED NOT NULL, "
            . "`userID` BIGINT UNSIGNED NULL, "
            . "`publishDate` VARCHAR(45) NULL, "
            . "`content` TEXT NOT NULL, "
            . "UNIQUE (`reactionID`), "
            . "FOREIGN KEY (`parentID`) REFERENCES `reactions` (`reactionID`), "
            . "FOREIGN KEY (`articleID`) REFERENCES `articles` (`articleID`), "
            . "FOREIGN KEY (`userID`) REFERENCES `users` (`userID`))"
        );

        $this->database->query("PRAGMA foreign_keys = ON");

        $this->database->query(
            "INSERT INTO `articles` (`articleID`, `title`, `content`) VALUES (1, 'Hello', 'Some content')"
        );

        $this->database->query(
            "INSERT INTO `users` (`userID`, `name`) VALUES (1, 'Sam'), (2, 'Som'), (3, 'En'), (4, 'Gert')"
        );
    }

    /**
     * Test
     *
     * @return void
     */
    public function testScoresAreAveraged()
    {
        $reaction = new App\Models\Reactions();
        $reaction->userID = '1';
        $reaction->content = 'Rate me';
        $reaction->articleID = '1';

        App\Models\Reactions::add($this->database, $reaction);

        $score = new App\Models\Scores();
        $score->reactionID = 1;
        $score->userID = 2;
        $score->score = 3;

        App\Models\Scores::add($this->database, $score);

        $score = new App\Models\Scores();
        $score->reactionID = 1;
        $score->userID = 3;
        $score->score = 1;

        App\Models\Scores::add($this->database, $score);

        $reactions = App\Models\Reactions::getThread($this->database, '1');
        $reaction = $reactions[0];

        $this->assertEquals(2, $reaction['score']);
    }

    /**
     * Test
     *
     * @return void
     */
    public function testScoresIsRounded()
    {
        $reaction = new App\Models\Reactions();
        $reaction->userID = '1';
        $reaction->content = 'Rate me';
        $reaction->articleID = '1';

        App\Models\Reactions::add($this->database, $reaction);

        $score = new App\Models\Scores();
        $score->reactionID = 1;
        $score->userID = 2;
        $score->score = 3;

        App\Models\Scores::add($this->database, $score);

        $score = new App\Models\Scores();
        $score->reactionID = 1;
        $score->userID = 3;
        $score->score = 1;

        App\Models\Scores::add($this->database, $score);

        $score = new App\Models\Scores();
        $score->reactionID = 1;
        $score->userID = 4;
        $score->score = 1;

        App\Models\Scores::add($this->database, $score);

        $reactions = App\Models\Reactions::getThread($this->database, '1');
        $reaction = $reactions[0];

        $this->assertEquals(2, $reaction['score']);
    }

    /**
     * Test
     *
     * @return void
     */
    public function testScoreOnePerUser()
    {
        $reaction = new App\Models\Reactions();
        $reaction->userID = 1;
        $reaction->content = 'Rate me';
        $reaction->articleID = '1';

        App\Models\Reactions::add($this->database, $reaction);

        $score = new App\Models\Scores();
        $score->reactionID = 1;
        $score->userID = 2;
        $score->score = 3;

        App\Models\Scores::add($this->database, $score);

        $score = new App\Models\Scores();
        $score->reactionID = 1;
        $score->userID = 2;
        $score->score = 1;

        App\Models\Scores::add($this->database, $score);

        $reactions = App\Models\Reactions::getThread($this->database, '1');
        $reaction = $reactions[0];

        $this->assertEquals(1, $reaction['score']);
    }
}
