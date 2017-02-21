<?php
namespace Hkt\Psr7Asset;

use Psr\Http\Message\ServerRequestInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Interop\Http\ServerMiddleware\DelegateInterface;

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
     * @param DelegateInterface $delegate
     *
     * @return ResponseInterface
     *
     */
    public function process(ServerRequestInterface $request, DelegateInterface $delegate)
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

        return $delegate($request);
    }
}
