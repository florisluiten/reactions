<?php
/**
 * Index - Start point for any HTTP request
 *
 * @author  Floris Luiten <floris@florisluiten.nl>
 * @package Reactions
 */
require_once '../src/Bootstrap.php';

$environment = require dirname(__FILE__) . '/../env.php';

$database = new \PDO($environment['PDO']['DSN'], $environment['PDO']['username'], $environment['PDO']['password']);

$database->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

$response = new \Fluiten\Reactions\Response\Http($database);
echo $response->handleRequest(new \Fluiten\Reactions\Request\Http($_SERVER));
