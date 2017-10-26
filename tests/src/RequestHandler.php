<?php

namespace Hkt\Psr7Asset;

use Psr\Http\Message\ServerRequestInterface;
use Webimpress\HttpMiddlewareCompatibility\HandlerInterface;
use Zend\Diactoros\Response;

/**
 * Class RequestHandler from http-interop/http-middleware:^0.5
 */
class RequestHandler implements HandlerInterface
{
    public function handle(ServerRequestInterface $request)
    {
        return new Response(null, 404);
    }
}
