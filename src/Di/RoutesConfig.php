<?php
namespace Hkt\Psr7Asset\Di;

use Aura\Di\Container;
use Aura\Di\ContainerConfig;
use Zend\Expressive\Router\Route;
use Zend\Expressive\Router\RouterInterface;

class RoutesConfig extends ContainerConfig
{
    public function define(Container $di)
    {
    }

    public function modify(Container $di)
    {
        $router = $di->get(RouterInterface::class);
        // Only works with FastRoute @see https://github.com/zendframework/zend-expressive-router/issues/10
        $router->addRoute(new Route('/asset/{vendor}/{package}/{file:.*}', 'Hkt\Psr7Asset\AssetAction', ['GET'], 'hkt/psr7-asset:route'));
    }
}
