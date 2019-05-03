<?php
/**
 * Test for Response\Http
 *
 * @package    Reactions
 * @author     Floris Luiten <floris@florisluiten.nl>
 */

declare(strict_types=1);

namespace Fluiten\Reactions\Tests;

use \Fluiten\Reactions as App;

class ResponseHttpTest extends \PHPUnit\Framework\TestCase
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
    }

    /**
     * Test
     *
     * @return void
     */
    public function testHandleReponseRequiresRequest()
    {
        $response = new App\Response\Http($this->database);

        $this->expectException(\Error::class);

        $response->handleRequest('string');
    }

    /**
     * Test
     *
     * @return void
     */
    public function testHandleReponseAcceptsRequest()
    {
        $response = new App\Response\Http($this->database);

        $this->assertNotNull($response->handleRequest(new App\Request\Http(array('REQUEST_URI' => '/news/1234'))));
    }
}
