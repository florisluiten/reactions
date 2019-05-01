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

    /**
     * Test retreiving reactions as thread. Should represent the following
     * structure:
     *
     *  1             reactionID:  1, parentID: null
     *   ├─1.1        reactionID:  4, parentID: 1
     *   │  ├ 1.1.1   reactionID:  6, parentID: 4
     *   │  ├ 1.1.2   reactionID:  7, parentID: 4
     *   │  └ 1.1.3   reactionID:  9, parentID: 4
     *   └─1.2        reactionID:  5, parentID: 1
     *      └ 1.2.1   reactionID:  8, parentID: 5
     *  2             reactionID:  2, parentID: null
     *   ├─2.1        reactionID:  3, parentID: 2
     *   └─2.2        reactionID: 10, parentID: 2
     *
     * @return void
     */
    public function testGetThread()
    {
        $this->database->query(
            "INSERT INTO `reactions` (`reactionID`, `articleID`, `parentID`, `content`, `userID`) "
            . "VALUES (1, 1, null, '1', 1)"
        );

        $this->database->query(
            "INSERT INTO `reactions` (`reactionID`, `articleID`, `parentID`, `content`, `userID`) "
            . "VALUES (2, 1, null, '2', 1)"
        );

        $this->database->query(
            "INSERT INTO `reactions` (`reactionID`, `articleID`, `parentID`, `content`, `userID`) "
            . "VALUES (3, 1, 2, '2.1', 1)"
        );

        $this->database->query(
            "INSERT INTO `reactions` (`reactionID`, `articleID`, `parentID`, `content`, `userID`) "
            . "VALUES (4, 1, 1, '1.1', 1)"
        );

        $this->database->query(
            "INSERT INTO `reactions` (`reactionID`, `articleID`, `parentID`, `content`, `userID`) "
            . "VALUES (5, 1, 1, '1.2', 1)"
        );

        $this->database->query(
            "INSERT INTO `reactions` (`reactionID`, `articleID`, `parentID`, `content`, `userID`) "
            . "VALUES (6, 1, 4, '1.1.1', 1)"
        );

        $this->database->query(
            "INSERT INTO `reactions` (`reactionID`, `articleID`, `parentID`, `content`, `userID`) "
            . "VALUES (7, 1, 4, '1.1.2', 1)"
        );

        $this->database->query(
            "INSERT INTO `reactions` (`reactionID`, `articleID`, `parentID`, `content`, `userID`) "
            . "VALUES (8, 1, 5, '1.2.1', 1)"
        );

        $this->database->query(
            "INSERT INTO `reactions` (`reactionID`, `articleID`, `parentID`, `content`, `userID`) "
            . "VALUES (9, 1, 4, '1.1.3', 1)"
        );

        $this->database->query(
            "INSERT INTO `reactions` (`reactionID`, `articleID`, `parentID`, `content`, `userID`) "
            . "VALUES (10, 1, 2, '2.2', 1)"
        );

        $answer = App\Models\Reactions::getThread($this->database, '1');

        $this->assertNotEmpty($answer);

        $this->assertCount(2, $answer);

        $this->assertCount(2, $answer[0]['children']);
        $this->assertCount(2, $answer[1]['children']);
        $this->assertCount(3, $answer[0]['children'][0]['children']);
        $this->assertCount(0, $answer[0]['children'][0]['children'][0]['children']);

        $this->assertSame('1', $answer[0]['content']);
        $this->assertSame('1.1', $answer[0]['children'][0]['content']);
        $this->assertSame('1.1.1', $answer[0]['children'][0]['children'][0]['content']);
        $this->assertSame('1.1.2', $answer[0]['children'][0]['children'][1]['content']);
        $this->assertSame('1.1.3', $answer[0]['children'][0]['children'][2]['content']);
        $this->assertSame('1.2', $answer[0]['children'][1]['content']);
        $this->assertSame('1.2.1', $answer[0]['children'][1]['children'][0]['content']);
        $this->assertSame('2', $answer[1]['content']);
        $this->assertSame('2.1', $answer[1]['children'][0]['content']);
        $this->assertSame('2.2', $answer[1]['children'][1]['content']);
    }
}
