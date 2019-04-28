<?php
/**
 * Test for Response\Http
 *
 * @author     Floris Luiten <floris@florisluiten.nl>
 * @package    Reactions
 * @subpackage Tests
 */

declare(strict_types=1);

namespace Fluiten\Reaction\Tests;

use \Fluiten\Reactions as App;

class ResponseHttpTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Test
     *
     * @return void
     */
    public function testHandleReponseRequiresRequest()
    {
        $response = new App\Response\Http();

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
        $response = new App\Response\Http();

        $this->assertNotNull($response->handleRequest(new App\Request\Http()));
    }
}
