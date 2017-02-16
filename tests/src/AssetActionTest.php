<?php
namespace Hkt\Psr7Asset;

use Hkt\Psr7Asset\AssetResponder;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequestFactory;

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

        $this->responder = new AssetResponder();

        $this->action = new AssetAction($service, $this->responder);
    }

    public function test__invoke()
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
        $response = new Response();
        $actual = $this->action->__invoke($request, $response);

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
