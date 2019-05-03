<?php
/**
 * Test for Request\Http
 *
 * @package    Reactions
 * @author     Floris Luiten <floris@florisluiten.nl>
 */

declare(strict_types=1);

namespace Fluiten\Reactions\Tests;

use \Fluiten\Reactions as App;

class RequestHttpTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Test
     *
     * @return void
     */
    public function testGetPath()
    {
        $this->assertEquals(
            '/home',
            (new App\Request\Http(array('REQUEST_URI' => '/home')))->getPath()
        );

        $this->assertEquals(
            '/home/',
            (new App\Request\Http(array('REQUEST_URI' => '/home/')))->getPath()
        );

        $this->assertEquals(
            '/some/where/over/the/rainbow',
            (new App\Request\Http(array('REQUEST_URI' => '/some/where/over/the/rainbow?way=up&high')))->getPath()
        );
    }
}
