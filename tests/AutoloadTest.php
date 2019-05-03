<?php
/**
 * Test for autoloader
 *
 * @package    Reactions
 * @author     Floris Luiten <floris@florisluiten.nl>
 */

declare(strict_types=1);

namespace Fluiten\Reactions\Tests;

class AutoloaderTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Test
     *
     * @runInSeparateProcess
     *
     * @return void
     */
    public function testAutoloadFindsClass()
    {
        require APP_DIR . 'Autoload.php';
        $this->assertNotNull(new \Fluiten\Reactions\Request\Http(array('REQUEST_URI' => '/news/1234')));
    }
}
