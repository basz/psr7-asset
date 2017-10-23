<?php

namespace Hkt\Psr7Asset;

use Interop\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response;

class RequestHandler implements RequestHandlerInterface
{
    public function handle(ServerRequestInterface $request)
    {
        return new Response(null, 404);
    }
}
