<?php
namespace Hkt\Psr7Asset;

use Psr\Http\Message\ResponseInterface;

class AssetResponderTest extends \PHPUnit_Framework_TestCase
{
    protected $responder;

    protected $asset_dir;

    public function setUp()
    {
        $this->asset_dir = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'web';

        $this->responder = new AssetResponder();
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

        $response = $this->responder->__invoke($response);

        $this->assertInstanceOf(ResponseInterface::class, $response);

        $actual = $response->status->getCode();
        $this->assertSame(200, $actual);

        $actual = $response->content->getType();
        $this->assertSame($type, $actual);

        $content = $response->content->get();
        ob_start();
        $content();
        $actual = ob_get_clean();

        $expect = file_get_contents($path);
        $this->assertSame($expect, $actual);
    }

    public function test__invoke_NotFound()
    {
        $path = null;
        $type = null;
        $response = $this->responder->__invoke($path, $type);

        $this->assertInstanceOf(ResponseInterface::class, $response);

        $actual = $response->status->getCode();
        $this->assertSame(404, $actual);

        $actual = $response->content->getType();
        $this->assertSame('', $actual);

        $content = $response->content->get();
        $this->assertSame('', $actual);
    }
}
