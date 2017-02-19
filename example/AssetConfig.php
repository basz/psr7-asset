<?php
namespace Hkt\Psr7Asset;

use Aura\Di\Container;
use Aura\Di\ContainerConfigInterface;

class AssetConfig implements ContainerConfigInterface
{
    public function define(Container $di)
    {
        $di->params['Hkt\Psr7Asset\AssetResponder']['responseFactory'] = $di->lazyNew('Http\Factory\Diactoros\ResponseFactory');

        // Alternative way than adding all into Constructor
        // $di->params['Hkt\Psr7Asset\AssetLocator']['map'] = [
        //     'vendor/package/css/hello.css' =>  '/path/to/web/css/test.css',
        //     'vendor/package' => dirname(dirname(__DIR__)) . '/web',
        // ];

        $di->set('Hkt\Psr7Asset\Router', $di->lazyNew('Hkt\Psr7Asset\Router'));
        $di->set('Hkt\Psr7Asset\AssetLocator', $di->lazyNew('Hkt\Psr7Asset\AssetLocator'));

        $di->params['Hkt\Psr7Asset\AssetAction'] = array(
            'domain' => $di->lazyNew('Hkt\Psr7Asset\AssetService'),
            'responder' => $di->lazyNew('Hkt\Psr7Asset\AssetResponder'),
            'router' => $di->lazyGet('Hkt\Psr7Asset\Router'),
        );

        $di->set('Hkt\Psr7Asset\AssetAction', $di->lazyNew('Hkt\Psr7Asset\AssetAction'));
    }

    public function modify(Container $di)
    {
        $assetLocator = $di->get('Hkt\Psr7Asset\AssetLocator');
        $assetLocator->set('vendor/package/css/hello.css', '/path/to/web/css/test.css');
        $assetLocator->set('vendor/package', dirname(dirname(__DIR__)) . '/web');
        // Map more paths and location as above.
    }
}
