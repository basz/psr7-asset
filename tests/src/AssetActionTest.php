<?php
namespace Hkt\Psr7Asset;

use Http\Factory\Diactoros\ResponseFactory;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\ServerRequestFactory;

class AssetActionTest extends TestCase
{
    protected $action;

    protected $asset_dir;

    protected $responder;

    public function setUp(): void
    {
        $this->asset_dir = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'public';

        $locator = new AssetLocator(array(
            'vendor/package' => $this->asset_dir,
        ));

        $service = new AssetService($locator);

        $responseFactory = new ResponseFactory();

        $this->responder = new AssetResponder($responseFactory);

        $router = new Router();

        $this->action = new AssetAction($service, $this->responder, $router);
    }

    public function testProcess(): void
    {
        $request = ServerRequestFactory::fromGlobals(
            [
                'REQUEST_URI' => '/asset/vendor/package/style.css'
            ],
            [],
            [],
            [],
            []
        );
        $requestHandler = new RequestHandler();
        $actual = $this->action->process($request, $requestHandler);

        $this->assertInstanceOf(ResponseInterface::class, $actual);

        $actual = $this->responder->getData();
        $expect = (object) array(
            'asset' => (object) array(
                'path' => $this->asset_dir . DIRECTORY_SEPARATOR . 'style.css',
                'type' => 'text/css',
            )
        );
        $this->assertEquals($expect, $actual);
    }
}
