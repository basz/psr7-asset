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
     * Constructor.
     *
     * @param Request $request A web request object.
     *
     * @param Response $response A web response object.
     *
     */
    public function __construct(
        AssetService $domain,
        AssetResponder $responder
    ) {
        $this->domain = $domain;
        $this->responder = $responder;
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
     * @return Response $response
     *
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next = null)
    {
        $docroot = $request->getServerParams()['DOCUMENT_ROOT'];
        $routeResult = $request->getAttribute('Zend\Expressive\Router\RouteResult');
        $params = $routeResult->getMatchedParams();
        $vendor = $params['vendor'];
        $package = $params['package'];
        $file = $params['file'];
        // public function __invoke($vendor, $package, $file)
        $asset = $this->domain->getAsset($vendor, $package, $file);
        $this->responder->setData(array('asset' => $asset));
        return $this->responder->__invoke($response, $docroot);
    }
}
