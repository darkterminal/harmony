<?php

namespace App\Middleware;
use App\Middleware\System\Middleware;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

class MiddlewareName extends Middleware
{
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $response = $handler->handle($request);
        /**
         * TODO Tulis middleware anda disini
         */

        $response = new Response();
        return $response;
    }
}
