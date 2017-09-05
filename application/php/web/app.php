<?php

require_once __DIR__.'/../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Response;

$request = \Symfony\Component\HttpFoundation\Request::createFromGlobals();

$app = new \Silex\Application();

$app->get('/pingdom', function() {
	return new Response('Hi');
});


$app->run($request);