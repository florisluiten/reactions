<?php
/**
 * Test for functions
 *
 * @author     Floris Luiten <floris@florisluiten.nl>
 * @package    Reactions
 * @subpackage Tests
 */

declare(strict_types=1);

namespace Fluiten\Reactions\Tests;

use \Fluiten\Reactions as App;

class FunctionsTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Test - Convert a flat array with parentIDs to a tree. The tree
     * should look like this:
     *
     *  1             (reactionID:  1, parentID: null)
     *   ├─1.1        (reactionID:  4, parentID: 1)
     *   │  ├ 1.1.1   (reactionID:  6, parentID: 4)
     *   │  ├ 1.1.2   (reactionID:  7, parentID: 4)
     *   │  └ 1.1.3   (reactionID:  9, parentID: 4)
     *   └─1.2        (reactionID:  5, parentID: 1)
     *      └ 1.2.1   (reactionID:  8, parentID: 5)
     *  2             (reactionID:  2, parentID: null)
     *   ├─2.1        (reactionID:  3, parentID: 2)
     *   └─2.2        (reactionID: 10, parentID: 2)
     *
     * @return void
     */
    public function testArrayToTree()
    {
        $array = array(
            array('ID' => 1, 'parentID' => null, 'content' => '1'),
            array('ID' => 2, 'parentID' => null, 'content' => '2'),
            array('ID' => 3, 'parentID' => 2, 'content' => '2.1'),
            array('ID' => 4, 'parentID' => 1, 'content' => '1.1'),
            array('ID' => 5, 'parentID' => 1, 'content' => '1.2'),
            array('ID' => 6, 'parentID' => 4, 'content' => '1.1.1'),
            array('ID' => 7, 'parentID' => 4, 'content' => '1.1.2'),
            array('ID' => 8, 'parentID' => 5, 'content' => '1.2.1'),
            array('ID' => 9, 'parentID' => 4, 'content' => '1.1.3'),
            array('ID' => 10, 'parentID' => 2, 'content' => '2.2')
        );

        $tree = App\arrayToTree($array);

        $this->assertNotEmpty($tree);

        $this->assertCount(2, $tree);
        $this->assertSame('1', $tree[0]['content']);
        $this->assertSame('2', $tree[1]['content']);

        $this->assertCount(2, $tree[0]['children']);
        $this->assertSame('1.1', $tree[0]['children'][0]['content']);
        $this->assertSame('1.2', $tree[0]['children'][1]['content']);

        $this->assertCount(2, $tree[1]['children']);
        $this->assertSame('2.1', $tree[1]['children'][0]['content']);
        $this->assertSame('2.2', $tree[1]['children'][1]['content']);

        $this->assertCount(3, $tree[0]['children'][0]['children']);
        $this->assertSame('1.1.1', $tree[0]['children'][0]['children'][0]['content']);
        $this->assertSame('1.1.2', $tree[0]['children'][0]['children'][1]['content']);
        $this->assertSame('1.1.3', $tree[0]['children'][0]['children'][2]['content']);

        $this->assertCount(1, $tree[0]['children'][1]['children']);
        $this->assertSame('1.2.1', $tree[0]['children'][1]['children'][0]['content']);
    }
}
