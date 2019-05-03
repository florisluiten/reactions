<?php
/**
 * Environment variables
 *
 * @package Reactions
 * @author  Floris Luiten <floris@florisluiten.nl>
 */

declare(strict_types=1);

/*
 * You can configure your environment here. Note that you should not commit
 * this file, since it contains your secrets!
 */

return array(
     // The database configuration
    'PDO' => array(
        // The DSN, eg "mysql:dbname=reactions;host=localhost"
        'DSN' => 'mysql:dbname=reactions;host=localhost',
        // The username for the database
        'username' => 'reactionsweb',
        // The password
        'password' => 'mysecret123'
    )
);
