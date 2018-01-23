# Psr7Asset

Asset management for PHP. This package is a fork of [Aura.Asset_Bundle](https://github.com/friendsofaura/Aura.Asset_Bundle).

## Foreword

### Requirements

* [PSR-7 implementation](https://packagist.org/providers/psr/http-message-implementation).
* [PSR-15 implementation](https://packagist.org/packages/psr/http-server-middleware)
* [Proposed PSR-17 implementation](https://github.com/http-interop/http-factory)

If you are not familiar with both, choose  [http-interop/http-factory-diactoros](https://packagist.org/packages/http-interop/http-factory-diactoros)

### Installation

```bash
composer require hkt/psr7-asset http-interop/http-factory-diactoros
```

### Tests

[![Build Status](https://travis-ci.org/harikt/psr7-asset.png?branch=master)](https://travis-ci.org/harikt/psr7-asset)

```bash
composer install
vendor/bin/phpunit
```

or you may use;

```bash
composer install
composer check
```


### PSR Compliance

This attempts to comply with [PSR-1][], [PSR-2][], [PSR-4][], [PSR-7][], [PSR-15][] and the proposed [PSR-17][]. If
you notice compliance oversights, please send a patch via pull request.

[PSR-1]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-1-basic-coding-standard.md
[PSR-2]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md
[PSR-4]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-4-autoloader.md
[PSR-7]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-7-http-message.md
[PSR-15]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-15-request-handlers.md
[PSR-17]: https://github.com/php-fig/fig-standards/blob/master/proposed/http-factory/http-factory.md

## Structure of Package

Assume you have a `Vendor.Package`. Your assets can be any where. Consider it is in the
`public` folder. The folder names `css`, `images`, `js` can be according to your preffered name.


```bash
├── src
│   ├── Cli
│   └── Web
├── tests
└── public
    ├── css
    │   └── some.css
    ├── images
    │   ├── another.jpg
    │   └── some.png
    └── js
        └── hello.js
```

Assuming you have the same structure, now in your template you can point
to `/asset/vendor/package/css/some.css`, `/asset/vendor/package/js/hello.js`, `/asset/vendor/package/images/another.jpg`.

## Routing

The library can be used with any framework. So it makes use of `preg_match` under the hood. The default regular expression is `/\/asset\/([a-zA-Z0-9-_]+)\/([a-zA-Z0-9-_]+)\/(.*)/` .

You can modify the regular expression when intantiating the `Router` object which is passed as 3rd argument to `AssetAction`.

```php
<?php
$locator   = new Hkt\Psr7Asset\AssetLocator();
$service   = new Hkt\Psr7Asset\AssetService($locator);
$responder = new Hkt\Psr7Asset\AssetResponder(new Http\Factory\Diactoros\ResponseFactory());
$router    = new Hkt\Psr7Asset\Router();
$assetAction = new Hkt\Psr7Asset\AssetAction($service, $responder, $router);

// ... more code
$assetAction->process($request, $requestHandler)
```

## Zend Expressive

If you are using `zend expressive you can configure,

### AuraRouter

```php
<?php
$route = new \Zend\Expressive\Router\Route('/asset/{vendor}/{package}/{file}', 'Hkt\Psr7Asset\AssetAction', ['GET'], 'hkt/psr7-asset:route');
$route->setOptions([
    'tokens' => [
        'file' => '(.*)'
    ]
]);
$router->addRoute($route);
```

### FastRoute

```php
<?php
$router->addRoute(new \Zend\Expressive\Router\Route('/asset/{vendor}/{package}/{file:.*}', 'Hkt\Psr7Asset\AssetAction', ['GET'], 'hkt/psr7-asset:route'));
```

> NB : Make sure you have set the service `Hkt\Psr7Asset\AssetAction` to the Di container.

From your view you can use as

```php
<?php
$this->url('hkt/psr7-asset:route', [
    'vendor' => 'vendor',
    'package' => 'package',
    'file' => '/css/bootstrap.min.css'
]);
```

This will return `/asset/vendor/package/css/bootstrap.min.css`.

## Mapping

With the help of mapping the `vendor/package` or directly the path you can alter the result it returns.

Eg : 

```php
$locator->set('vendor/package', '/full/path/to/vendor/package');
```

### Overriding css, js, images

Like [puli](https://github.com/puli) it is possible that you can override the style sheet, images, js etc for the downloaded package. You just need to map it. No magic under the hood.

Eg : 

```php
$locator->set('vendor/package', '/full/path/to/vendor/package');
$locator->set('vendor/package/css/style.css', '/full/path/to/vendor/package/public/css/style.css');
// override vendor/package style sheet, same applies for js and images
$locator->set('vendor/package/css/style.css', '/full/path/to/application/specific/public/css/style.css');
```

## Caching

The asset files are served by PHP. There is an experimental repo 
https://github.com/harikt/psr7-asset-cache that can do caching.
So files can be served by the web server itself.

## Configuration via Aura.Di

Pass `Hkt\Psr7Asset\Container\AssetConfig` to your
[Container Builder](http://auraphp.com/packages/3.x/Di/config.html#1-1-8).
Don't forget to set the service `Interop\Http\Factory\ResponseFactoryInterface`
as below.

```php
<?php
namespace Vendor\Package;

use Aura\Di\Container;
use Aura\Di\ContainerConfigInterface;

class AppConfig implements ContainerConfigInterface
{
    public function define(Container $di)
    {
        // add one of the http-interop/http-factory library
        $di->set('Interop\Http\Factory\ResponseFactoryInterface', $di->lazyNew('Http\Factory\Diactoros\ResponseFactory'));
    }

    public function modify(Container $di)
    {
        // Map more paths and location as above.
        $assetLocator = $di->get('Hkt\Psr7Asset\AssetLocator');
        // path to exact location
        $assetLocator->set('vendor/package/css/hello.css', '/path/to/web/css/test.css');
        // path to folder
        $assetLocator->set('vendor/package', dirname(dirname(__DIR__)) . '/public');
    }
}
```
