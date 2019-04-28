<?php
/**
 * Index - Start point for any HTTP request
 *
 * @author  Floris Luiten <floris@florisluiten.nl>
 * @package Reactions
 */
require_once '../src/Bootstrap.php';

$response = new \Fluiten\Reactions\Response\Http();
echo $response->handleRequest(new \Fluiten\Reactions\Request\Http($_SERVER));
