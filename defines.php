<?php
/**
 * Defines - Constants used in the application
 *
 * @package Reactions
 * @author  Floris Luiten <floris@florisluiten.nl>
 */

declare(strict_types=1);

define('APP_DIR', realpath(__DIR__ . '/src') . '/');

/*
 * @var SORT_NEWEST_FIRST Flag for indicating to sort from new to old
 */

define('SORT_NEWEST_FIRST', 0);

/*
 * @var SORT_OLDEST_FIRST Flag for indicating to sort from old to new
 */

define('SORT_OLDEST_FIRST', 1);
