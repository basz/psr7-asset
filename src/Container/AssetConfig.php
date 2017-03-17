<?php
namespace Hkt\Psr7Asset\Container;

use Aura\Di\Container;
use Aura\Di\ContainerConfigInterface;

class AssetConfig implements ContainerConfigInterface
{
    public function define(Container $di)
    {
        $di->params['Hkt\Psr7Asset\AssetService']['locator'] = $di->lazyGet('Hkt\Psr7Asset\AssetLocator');

        $di->params['Hkt\Psr7Asset\AssetAction'] = array(
            'domain' => $di->lazyNew('Hkt\Psr7Asset\AssetService'),
            'responder' => $di->lazyNew('Hkt\Psr7Asset\AssetResponder'),
            'router' => $di->lazyNew('Hkt\Psr7Asset\Router'),
        );

        $di->params['Hkt\Psr7Asset\AssetResponder']['responseFactory'] = $di->lazyGet('Interop\Http\Factory\ResponseFactoryInterface');

        $di->set('Hkt\Psr7Asset\AssetLocator', $di->lazyNew('Hkt\Psr7Asset\AssetLocator'));
        $di->set('Hkt\Psr7Asset\AssetAction', $di->lazyNew('Hkt\Psr7Asset\AssetAction'));
    }

    public function modify(Container $di)
    {
    }
}
