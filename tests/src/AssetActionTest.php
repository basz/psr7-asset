<?php
namespace Hkt\Psr7Asset;

use Hkt\Psr7Asset\AssetResponder;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequestFactory;
use Http\Factory\Diactoros\ResponseFactory;

class AssetActionTest extends \PHPUnit_Framework_TestCase
{
    protected $action;

    protected $asset_dir;

    protected $responder;

    public function setUp()
    {
        $this->asset_dir = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'web';

        $locator = new AssetLocator(array(
            'vendor/package' => $this->asset_dir,
        ));

        $service = new AssetService($locator);

        $responseFactory = new ResponseFactory();

        $this->responder = new AssetResponder($responseFactory);

        $router = new Router();

        $this->action = new AssetAction($service, $this->responder, $router);
    }

    public function testProcess()
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
        $delegate = new Delegate();
        $actual = $this->action->process($request, $delegate);

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
