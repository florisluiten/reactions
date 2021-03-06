<?php
/**
 * Test for Models\Reactions
 *
 * @package    Reactions
 * @author     Floris Luiten <floris@florisluiten.nl>
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
            . "`name` VARCHAR(100) NOT NULL, "
            . "`image` VARCHAR(100) NULL) "
        );

        $this->database->query(
            "CREATE TABLE `reactions` ( `reactionID` INTEGER PRIMARY KEY AUTOINCREMENT, "
            . "`parentID` BIGINT UNSIGNED NULL DEFAULT NULL, "
            . "`articleID` BIGINT UNSIGNED NOT NULL, "
            . "`userID` BIGINT UNSIGNED NOT NULL, "
            . "`publishDate` DATETIME NULL, "
            . "`content` TEXT NOT NULL, "
            . "UNIQUE (`reactionID`), "
            . "FOREIGN KEY (`parentID`) REFERENCES `reactions` (`reactionID`), "
            . "FOREIGN KEY (`articleID`) REFERENCES `articles` (`articleID`), "
            . "FOREIGN KEY (`userID`) REFERENCES `users` (`userID`))"
        );

        $this->database->query(
            "CREATE TABLE `reactionScores` ( `reactionID` BIGINT UNSIGNED NOT NULL, "
            . "`userID` BIGINT UNSIGNED NOT NULL, "
            . "`score` TINYINT NOT NULL, "
            . "PRIMARY KEY (`reactionID`, `userID`), "
            . "FOREIGN KEY (`reactionID`) REFERENCES `reactions` (`reactionID`),"
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
            "INSERT INTO `reactions` (`reactionID`, `articleID`, `userID`, `publishDate`, `content`) "
            . "VALUES (1, 1, 1, '2018-01-01 12:12:12', 'Some content')"
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
            "INSERT INTO `reactions` (`reactionID`, `articleID`, `userID`, `publishDate`, `content`) "
            . "VALUES (1, 1, 1, '2018-01-01 12:12:12', 'Some content')"
        );

        $resource = App\Models\Reactions::queryByArticle($this->database, '1');
        $resource->execute();

        $answer = $resource->fetch();
        $this->assertNotEmpty($answer);

        $this->assertInstanceOf(App\Models\Reactions::class, $answer);

        $this->assertSame('Some content', $answer->content);
    }

    /**
     * Test
     *
     * @return void
     */
    public function testGetThread()
    {
        $this->setupThread();

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

    /**
     * Test
     *
     * @return void
     */
    public function testGetThreadReversed()
    {
        $this->setupThread();

        $answer = App\Models\Reactions::getThread($this->database, '1', \SORT_NEWEST_FIRST);

        $this->assertSame('2', $answer[0]['content']);
        $this->assertSame('1', $answer[1]['content']);

        $this->assertSame('2.2', $answer[0]['children'][0]['content']);
        $this->assertSame('2.1', $answer[0]['children'][1]['content']);

        $this->assertSame('1.2', $answer[1]['children'][0]['content']);
        $this->assertSame('1.1', $answer[1]['children'][1]['content']);

        $this->assertSame('1.1.3', $answer[1]['children'][1]['children'][0]['content']);
        $this->assertSame('1.1.2', $answer[1]['children'][1]['children'][1]['content']);
        $this->assertSame('1.1.1', $answer[1]['children'][1]['children'][2]['content']);

        $this->assertSame('1.2.1', $answer[1]['children'][0]['children'][0]['content']);
    }

    /**
     * Test
     *
     * @return void
     */
    public function testGetByArticleSorting()
    {
        $this->setupThread();

        $resource = App\Models\Reactions::queryByArticle($this->database, '1');
        $resource->execute();

        $this->assertSame('1', ($resource->fetch())->content);
        $this->assertSame('2', ($resource->fetch())->content);
        $this->assertSame('2.1', ($resource->fetch())->content);
        $this->assertSame('1.1', ($resource->fetch())->content);
        $this->assertSame('1.2', ($resource->fetch())->content);
        $this->assertSame('1.1.1', ($resource->fetch())->content);
        $this->assertSame('1.1.2', ($resource->fetch())->content);
        $this->assertSame('1.2.1', ($resource->fetch())->content);
        $this->assertSame('1.1.3', ($resource->fetch())->content);
        $this->assertSame('2.2', ($resource->fetch())->content);
        $this->assertEmpty($resource->fetch());

        $resource = App\Models\Reactions::queryByArticle($this->database, '1', \SORT_NEWEST_FIRST);
        $resource->execute();

        $this->assertSame('2.2', ($resource->fetch())->content);
        $this->assertSame('1.1.3', ($resource->fetch())->content);
        $this->assertSame('1.2.1', ($resource->fetch())->content);
        $this->assertSame('1.1.2', ($resource->fetch())->content);
        $this->assertSame('1.1.1', ($resource->fetch())->content);
        $this->assertSame('1.2', ($resource->fetch())->content);
        $this->assertSame('1.1', ($resource->fetch())->content);
        $this->assertSame('2.1', ($resource->fetch())->content);
        $this->assertSame('2', ($resource->fetch())->content);
        $this->assertSame('1', ($resource->fetch())->content);
        $this->assertEmpty($resource->fetch());
    }

    /**
     * Test
     *
     * @return void
     */
    public function testCreate()
    {
        $reaction = new App\Models\Reactions();
        $reaction->articleID = 1;
        $reaction->userID = 1;
        $reaction->content = 'Blaat';
        $reaction->parentID = null;

        $reply = new App\Models\Reactions();
        $reply->articleID = 1;
        $reply->userID = 2;
        $reply->parentID = 1;
        $reply->content = 'Oh, really?!';

        $this->assertTrue(App\Models\Reactions::add($this->database, $reaction));
        $this->assertTrue(App\Models\Reactions::add($this->database, $reply));

        $answer = App\Models\Reactions::getThread($this->database, '1');

        $this->assertNotEmpty($answer);

        $this->assertCount(1, $answer);
        $this->assertCount(1, $answer[0]['children']);

        $this->assertSame('Blaat', $answer[0]['content']);
        $this->assertSame('Oh, really?!', $answer[0]['children'][0]['content']);
    }

    /**
     * Test
     *
     * @return void
     */
    public function testScoreDefaultsToZero()
    {
        $reaction = new App\Models\Reactions();
        $reaction->userID = '1';
        $reaction->content = 'Rate me';
        $reaction->articleID = '1';

        App\Models\Reactions::add($this->database, $reaction);

        $reactions = App\Models\Reactions::getThread($this->database, '1');
        $reaction = $reactions[0];

        $this->assertSame('0', $reaction['score']);
    }

    /**
     * Test
     *
     * @return void
     */
    public function testGetById()
    {
        $this->database->query(
            "INSERT INTO `reactions` (`reactionID`, `articleID`, `userID`, `publishDate`, `content`) "
            . "VALUES (1, 1, 1, '2018-01-01 12:12:12', 'Some content')"
        );

        $answer = App\Models\Reactions::getById($this->database, '1');

        $this->assertNotEmpty($answer);

        $this->assertInstanceOf(App\Models\Reactions::class, $answer);

        $this->assertSame('Some content', $answer->content);

        $this->assertEmpty(App\Models\Reactions::getById($this->database, '42'));
    }

    /**
     *
     * Setup database for a thread like this:
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
    private function setupThread()
    {
        $this->database->query(
            "INSERT INTO `reactions` (`reactionID`, `articleID`, `parentID`, `content`, `userID`, `publishDate`) "
            . "VALUES (1, 1, null, '1', 1, '2019-04-25 16:20:00')"
        );

        $this->database->query(
            "INSERT INTO `reactions` (`reactionID`, `articleID`, `parentID`, `content`, `userID`, `publishDate`) "
            . "VALUES (2, 1, null, '2', 1, '2019-04-25 16:24:00')"
        );

        $this->database->query(
            "INSERT INTO `reactions` (`reactionID`, `articleID`, `parentID`, `content`, `userID`, `publishDate`) "
            . "VALUES (3, 1, 2, '2.1', 1, '2019-04-25 16:55:00')"
        );

        $this->database->query(
            "INSERT INTO `reactions` (`reactionID`, `articleID`, `parentID`, `content`, `userID`, `publishDate`) "
            . "VALUES (4, 1, 1, '1.1', 1, '2019-04-25 17:34:00')"
        );

        $this->database->query(
            "INSERT INTO `reactions` (`reactionID`, `articleID`, `parentID`, `content`, `userID`, `publishDate`) "
            . "VALUES (5, 1, 1, '1.2', 1, '2019-04-25 17:35:00')"
        );

        $this->database->query(
            "INSERT INTO `reactions` (`reactionID`, `articleID`, `parentID`, `content`, `userID`, `publishDate`) "
            . "VALUES (6, 1, 4, '1.1.1', 1, '2019-04-25 17:36:00')"
        );

        $this->database->query(
            "INSERT INTO `reactions` (`reactionID`, `articleID`, `parentID`, `content`, `userID`, `publishDate`) "
            . "VALUES (7, 1, 4, '1.1.2', 1, '2019-04-25 17:37:00')"
        );

        $this->database->query(
            "INSERT INTO `reactions` (`reactionID`, `articleID`, `parentID`, `content`, `userID`, `publishDate`) "
            . "VALUES (8, 1, 5, '1.2.1', 1, '2019-04-25 17:38:00')"
        );

        $this->database->query(
            "INSERT INTO `reactions` (`reactionID`, `articleID`, `parentID`, `content`, `userID`, `publishDate`) "
            . "VALUES (9, 1, 4, '1.1.3', 1, '2019-04-25 17:39:00')"
        );

        $this->database->query(
            "INSERT INTO `reactions` (`reactionID`, `articleID`, `parentID`, `content`, `userID`, `publishDate`) "
            . "VALUES (10, 1, 2, '2.2', 1, '2019-04-25 17:40:00')"
        );
    }
}
