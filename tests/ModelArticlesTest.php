<?php
/**
 * Test for Models\Article
 *
 * @author     Floris Luiten <floris@florisluiten.nl>
 * @package    Reactions
 * @subpackage Tests
 */

declare(strict_types=1);

namespace Fluiten\Reaction\Tests;

use \Fluiten\Reactions as App;

class ModelsArticlesTest extends \PHPUnit\Framework\TestCase
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
            "INSERT INTO `articles` (`articleID`, `title`, `content`) VALUES (1, 'Hello', 'Some content')"
        );
    }

    /**
     * Test
     *
     * @return void
     */
    public function testNotFound()
    {
        $resource = App\Models\Articles::queryById($this->database, '12');
        $resource->execute();

        $this->assertEmpty($resource->fetch());
    }

    /**
     * Test
     *
     * @return void
     */
    public function testFound()
    {
        $resource = App\Models\Articles::queryById($this->database, '1');
        $resource->execute();

        $answer = $resource->fetch();
        $this->assertInstanceOf(App\Models\Articles::class, $answer);

        $this->assertSame('Hello', $answer->title);
        $this->assertSame('Some content', $answer->content);
    }
}
