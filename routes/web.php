<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

$app->get('/', function (Request $request, Response $response) use (&$container) {
	
    return $container->get('view')->render($response, 'welcome.twig');

});

# or
# $app->get('/', 'HomeController:index')->setName('home');
