<?php

namespace Hkt\Psr7Asset;

use Webimpress\HttpMiddlewareCompatibility\HandlerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response;

class RequestHandler implements HandlerInterface
{
    public function handle(ServerRequestInterface $request)
    {
        return new Response(null, 404);
    }
}
