<?php
namespace Hkt\Psr7Asset;

use Hkt\Psr7Asset\AssetResponder;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequestFactory;
use Http\Factory\Diactoros\ResponseFactory;

class RouterTest extends \PHPUnit_Framework_TestCase
{
    protected $router;

    public function setUp()
    {
        $this->router = new Router();
    }

    public function testMatch()
    {
        $request = ServerRequestFactory::fromGlobals(
            [
                'REQUEST_URI' => '/asset/vendor-name/package_name/style.css'
            ],
            [],
            [],
            [],
            []
        );
        $route = $this->router->match($request);

        $expect = (object)[
            'vendor' => 'vendor-name',
            'package' => 'package_name',
            'file' => 'style.css',
        ];
        $this->assertEquals($expect, $route);
    }

    public function testMatchReturnFalse()
    {
        $request = ServerRequestFactory::fromGlobals(
            [
                'REQUEST_URI' => '/vendor/package/style.css'
            ],
            [],
            [],
            [],
            []
        );
        $route = $this->router->match($request);
        $this->assertFalse($route);
    }
}
