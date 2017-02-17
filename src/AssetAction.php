<?php
namespace Hkt\Psr7Asset;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 *
 * Maps an asset request to an asset response via the asset service.
 *
 */
class AssetAction
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
     */
    protected $routeRegx = '/\/asset\/([a-zA-Z0-9]+)\/([a-zA-Z0-9]+)\/(.*)/';

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
        AssetResponder $responder
    ) {
        $this->domain = $domain;
        $this->responder = $responder;
    }

    public function setRouteRegx($regx)
    {
        $this->routeRegx = $regx;
    }

    /**
     *
     * Invokes the Domain and Responder to return a Response.
     *
     * @param ServerRequestInterface $request
     *
     * @param ResponseInterface $response
     *
     * @param callable $next
     *
     * @return ResponseInterface
     *
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next = null)
    {
        $path = $request->getUri()->getPath();

        if (preg_match($this->routeRegx, $path, $matches)) {
            $vendor = $matches[1];
            $package = $matches[2];
            $file = $matches[3];

            $asset = $this->domain->getAsset($vendor, $package, $file);
            $this->responder->setData(array('asset' => $asset));
            return $this->responder->__invoke();
        }

        if ($next) {
            return $next($request, $response);
        }
    }
}
