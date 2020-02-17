<?php

namespace App\Middleware\System;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

class HelperMiddleware extends Middleware
{
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $response = $handler->handle($request);
        
        $this->container->get('view')->getEnvironment()->addGlobal('helper', [
            'storage' => $this->container['settings']['storage'],
        ]);

        $response = new Response();
        return $response;
    }
}
