<?php
namespace Hkt\Psr7Asset;

use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Response;
use Http\Factory\Diactoros\ResponseFactory;

class AssetResponderTest extends \PHPUnit_Framework_TestCase
{
    protected $responder;

    protected $asset_dir;

    public function setUp()
    {
        $this->asset_dir = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'public';
        $responseFactory = new ResponseFactory();
        $this->responder = new AssetResponder($responseFactory);
    }

    public function test__invoke_Ok()
    {
        $path = $this->asset_dir. DIRECTORY_SEPARATOR . 'style.css';
        $type = 'text/css';

        $asset = (object) array(
            'path' => $path,
            'type' => $type,
        );

        $this->responder->setData(array(
            'asset' => $asset,
        ));

        $response = $this->responder->__invoke();

        $this->assertInstanceOf(ResponseInterface::class, $response);

        $actual = $response->getStatusCode();
        $this->assertSame(200, $actual);

        $actual = $response->getHeaderLine('Content-Type');
        $this->assertSame($type, $actual);

        ob_start();
        echo (string) $response->getBody();
        $actual = ob_get_contents();
        ob_end_clean();

        $expect = file_get_contents($path);
        $this->assertSame($expect, $actual);
    }

    public function test__invoke_NotFound()
    {
        $this->setExpectedException('Hkt\Psr7Asset\Exception\FileNotReadable');
        $this->responder->__invoke();
    }
}
