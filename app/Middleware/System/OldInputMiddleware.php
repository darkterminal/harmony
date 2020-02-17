<?php

namespace App\Middleware\System;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

class OldInputMiddleware extends Middleware
{
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $response = $handler->handle($request);

        $this->container->get('view')->getEnvironment()->addGlobal('old', @$_SESSION['old']);
        $_SESSION['old'] = $request->getParams();

        $response = new Response();
        return $response;
    }
}
