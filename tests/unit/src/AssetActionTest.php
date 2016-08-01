<?php
namespace Hkt\Psr7Asset;

use Hkt\Psr7Asset\AssetResponder;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequestFactory;
use Zend\Expressive\Router\RouteResult;

class AssetActionTest extends \PHPUnit_Framework_TestCase
{
    protected $action;

    protected $asset_dir;

    public function setUp()
    {
        $this->asset_dir = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'web';

        $service = new AssetService(array(
            'vendor/package' => $this->asset_dir,
        ));

        $responder = new AssetResponder();

        $this->action = new AssetAction($service, $responder);
    }

    public function test__invoke()
    {
        $request = ServerRequestFactory::fromGlobals(
            [
                'path' => '/asset/vendor/package/style.css'
            ],
            [],
            [],
            [],
            []
        );
        // $request = $request->withPath();
        $response = new Response();
        $request->withAttribute(RouteResult::class);
        $responder = $this->action->__invoke($request, $response);

        $this->assertInstanceOf(Response::class, $responder);

        // $actual = $responder->getData();
        // $expect = (object) array(
        //     'asset' => (object) array(
        //         'path' => $this->asset_dir . DIRECTORY_SEPARATOR . 'style.css',
        //         'type' => 'text/css',
        //     )
        // );
        // $this->assertEquals($expect, $actual);
    }
}
