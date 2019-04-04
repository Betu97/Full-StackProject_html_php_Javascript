<?php

namespace SallePW\SlimApp\Controller\Middleware;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class Middleware2
{
    public function __invoke(Request $request, Response $response, callable $nextMiddleware)
    {
        $response->getBody()->write('Before2');

        /** @var Response $response */
        $response = $nextMiddleware($request, $response);

        $response->getBody()->write('After2');

        return $response;
    }
}