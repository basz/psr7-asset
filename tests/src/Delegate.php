<?php
namespace Hkt\Psr7Asset;

use Interop\Http\ServerMiddleware\DelegateInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response;

class Delegate implements DelegateInterface
{
    public function process(ServerRequestInterface $request)
    {
        $response = new Response();
        return $response->setStatusCode(404);
    }
}
