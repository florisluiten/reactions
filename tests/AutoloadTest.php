<?php
/**
 * Test for autoloader
 *
 * @author     Floris Luiten <floris@florisluiten.nl>
 * @package    Reactions
 * @subpackage Tests
 */

declare(strict_types=1);

namespace Fluiten\Reaction\Tests;

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
        require __DIR__ . '/../defines.php';
        require APP_DIR . 'Autoload.php';
        $this->assertNotNull(new \Fluiten\Reactions\Request\Http());
    }

    /**
     * Test
     *
     * @runInSeparateProcess
     *
     * @return void
     */
    public function testAutoloadNoIncludeFailure()
    {
        require __DIR__ . '/../defines.php';
        $this->expectException(\Error::class);
        $this->assertNull(new \Fluiten\Reactions\Request\Http());
    }
}
