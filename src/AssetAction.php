<?php

namespace Hkt\Psr7Asset;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Webimpress\HttpMiddlewareCompatibility\HandlerInterface;
use Webimpress\HttpMiddlewareCompatibility\MiddlewareInterface;
use const Webimpress\HttpMiddlewareCompatibility\HANDLER_METHOD;

/**
 *
 * Maps an asset request to an asset response via the asset service.
 *
 */
class AssetAction implements MiddlewareInterface
{
    /**
     *
     * A Responder to build the web response.
     *
     * @var AssetResponder
     *
     */
    protected $responder;

    /**
     *
     * A Domain object for assets.
     *
     * @var AssetService
     *
     */
    protected $domain;

    /**
     *
     * A Router object to extract the vendor/package/file from path
     *
     * @var Router
     *
     */
    protected $router;

    /**
     *
     * Constructor.
     *
     * @param AssetService $domain A Domain object for assets
     *
     * @param AssetResponder $responder A Responder to build the web response.
     *
     */
    public function __construct(
        AssetService $domain,
        AssetResponder $responder,
        Router $router
    ) {
        $this->domain = $domain;
        $this->responder = $responder;
        $this->router = $router;
    }

    /**
     *
     * Invokes the Domain and Responder to return a Response.
     *
     * @param ServerRequestInterface $request
     *
     * @param HandlerInterface $handler
     *
     * @return ResponseInterface
     *
     */
    public function process(ServerRequestInterface $request, HandlerInterface $handler): ResponseInterface
    {
        $route = $this->router->match($request);

        if ($route) {
            $asset = $this->domain->getAsset($route->vendor, $route->package, $route->file);
            $this->responder->setData(array('asset' => $asset));
            try {
                return $this->responder->__invoke();
            } catch (Exception\FileNotReadable $e) {
                // do nothing
            }
        }

        return $handler->{HANDLER_METHOD}($request);
    }
}
