<?php

namespace App\Middleware\System;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

class AuthMiddleware extends Middleware
{
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $response = $handler->handle($request);
        // Check if user not signed in
        if (!$this->container->auth->check()) {
            
            // display flash message
            $this->container->flash->addMessage('error', 'Please sing in before doing that');

            // redirect
            return $response->withRedirect($this->container->router->pathFor('auth.signin'));
        }

        $response = new Response();
        return $response;
    }
}
