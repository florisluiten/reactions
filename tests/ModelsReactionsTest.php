<?php
/**
 * Test for Models\Reactions
 *
 * @author     Floris Luiten <floris@florisluiten.nl>
 * @package    Reactions
 * @subpackage Tests
 */

declare(strict_types=1);

namespace Fluiten\Reactions\Tests;

use \Fluiten\Reactions as App;

class ModelsReactionsTest extends \PHPUnit\Framework\TestCase
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
            . "`name` VARCHAR(100) NOT NULL) "
        );

        $this->database->query(
            "CREATE TABLE `reactions` ( `reactionID` INTEGER PRIMARY KEY AUTOINCREMENT, "
            . "`parentID` BIGINT UNSIGNED NULL DEFAULT NULL, "
            . "`articleID` BIGINT UNSIGNED NOT NULL, "
            . "`score` INT NULL DEFAULT 0, "
            . "`userID` BIGINT UNSIGNED NOT NULL, "
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
    public function testGetEmptyReactions()
    {
        $resource = App\Models\Reactions::queryByArticle($this->database, '1');
        $resource->execute();

        $this->assertEmpty($resource->fetch());
    }

    /**
     * Test
     *
     * @return void
     */
    public function testGetOtherArticle()
    {
        $this->database->query(
            "INSERT INTO `reactions` (`reactionID`, `articleID`, `score`, `userID`, `publishDate`, `content`) "
            . "VALUES (1, 1, 0, 1, '2018-01-01 12:12:12', 'Some content')"
        );

        $resource = App\Models\Reactions::queryByArticle($this->database, '2');
        $resource->execute();

        $this->assertEmpty($resource->fetch());
    }

    /**
     * Test
     *
     * @return void
     */
    public function testGetCorrectArticle()
    {
        $this->database->query(
            "INSERT INTO `reactions` (`reactionID`, `articleID`, `score`, `userID`, `publishDate`, `content`) "
            . "VALUES (1, 1, 0, 1, '2018-01-01 12:12:12', 'Some content')"
        );

        $resource = App\Models\Reactions::queryByArticle($this->database, '1');
        $resource->execute();

        $answer = $resource->fetch();
        $this->assertNotEmpty($answer);

        $this->assertInstanceOf(App\Models\Reactions::class, $answer);

        $this->assertSame('Some content', $answer->content);
    }
}
