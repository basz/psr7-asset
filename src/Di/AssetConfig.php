<?php
namespace Hkt\Psr7Asset\Di;

use Aura\Di\Container;
use Aura\Di\ContainerConfig;
use Zend\Expressive\Router\Route;
use Zend\Expressive\Router\RouterInterface;

class AssetConfig extends ContainerConfig
{
    public function define(Container $di)
    {
        // set your configuration accordingly
        // $di->params['Hkt\Psr7Asset\AssetService']['types']['key'] = 'value';
        // $di->params['Hkt\Psr7Asset\AssetService']['map']['key'] = 'path';

        $di->params['Hkt\Psr7Asset\AssetAction'] = array(
            'domain' => $di->lazyNew('Hkt\Psr7Asset\AssetService'),
            'responder' => $di->lazyNew('Hkt\Psr7Asset\AssetResponder'),
        );

        $di->set('Hkt\Psr7Asset\AssetAction', $di->lazyNew('Hkt\Psr7Asset\AssetAction'));
    }

    public function modify(Container $di)
    {
    }
}
