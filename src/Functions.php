<?php
/**
 * Global functions
 *
 * @package Reactions
 * @author  Floris Luiten <floris@florisluiten.nl>
 */

declare(strict_types=1);

namespace Fluiten\Reactions;

/**
 * Transform an array with ID's and parentID's to a tree.
 *
 * @param array[] $array    The array with ID's and parentID's
 * @param integer $parentID The parentID for the current level
 *
 * @return array[] with "children" key set to the child elements
 */
function arrayToTree(array $array, $parentID = null)
{
    $tree = array();

    foreach ($array as $child) {
        if ($child['parentID'] === $parentID) {
            $child['children'] = arrayToTree($array, $child['ID']);
            $tree[] = $child;
        }
    }

    return $tree;
}
