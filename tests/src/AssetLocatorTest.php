<?php
namespace Hkt\Psr7Asset;

use PHPUnit\Framework\TestCase;

class AssetLocatorTest extends TestCase
{

    public function setUp()
    {
        $this->asset_dir = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'public';

        $this->locator = new AssetLocator(array(
            'vendor/package' => $this->asset_dir,
        ));
    }

    public function testSetHasAndGet()
    {
        $this->locator->set('hello', '/asset/hello.css');
        $this->assertTrue($this->locator->has('hello'));
        $this->assertSame('/asset/hello.css', $this->locator->get('hello'));
        $this->assertSame($this->asset_dir, $this->locator->get('vendor/package'));
    }

    public function testSetCanReplaceLocation()
    {
        $this->locator->set('vendor/package', '/another/vendor/hello.css');
        $this->assertSame('/another/vendor/hello.css', $this->locator->get('vendor/package'));
    }

    public function testGetThrowsException()
    {
        $this->expectException('Hkt\Psr7Asset\Exception\PathNotFound');
        $this->locator->get('another/vendor');
    }
}
