# Psr7Asset

Asset management for PHP. This package is a fork of [Aura.Asset_Bundle](https://github.com/friendsofaura/Aura.Asset_Bundle).

## Foreword

### Requriements

* [PSR-7 implementation](https://packagist.org/providers/psr/http-message-implementation).
* [Proposed PSR-17 implementation](https://github.com/http-interop/http-factory)

If you are not familiar with both, choose  [http-interop/http-factory-diactoros](https://packagist.org/packages/http-interop/http-factory-diactoros)

### Installation

```
composer require hkt/psr7-asset http-interop/http-factory-diactoros
```

### Tests

[![Build Status](https://travis-ci.org/harikt/psr7-asset.png?branch=master)](https://travis-ci.org/harikt/psr7-asset)

```bash
composer install
vendor/bin/phpunit
```

### PSR Compliance

This attempts to comply with [PSR-1][], [PSR-2][], [PSR-4][], [PSR-7][] and the proposed [PSR-17][]. If
you notice compliance oversights, please send a patch via pull request.

[PSR-1]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-1-basic-coding-standard.md
[PSR-2]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md
[PSR-4]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-4-autoloader.md

[PSR-7]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-7-http-message.md
[PSR-17]: https://github.com/php-fig/fig-standards/blob/master/proposed/http-factory/http-factory.md

## Structure of Package

Assume you have a `Vendor.Package`. Your assets can be any where. Consider it is in the
`web` folder. The folder names `css`, `images`, `js` can be according to your preffered name.


```bash
├── src
│   ├── Cli
│   └── Web
├── tests
└── web
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

You can modify the regular expression by intantiating a `Router` object
and passing it as 3rd argument to `AssetAction`

```php
$router = new Hkt\Psr7Asset\Router('your-regx');
// ... more code
$assetAction = new Hkt\Psr7Asset\AssetAction($service, $responder, $router);
```

## Zend Expressive

If you are using `zend expressive fast route` you can configure as,

```php
$router->addRoute(new \Zend\Expressive\Router\Route('/asset/{vendor}/{package}/{file:.*}', 'Hkt\Psr7Asset\AssetAction', ['GET'], 'hkt/psr7-asset:route'));
```

> NB : Make sure you have set the service `Hkt\Psr7Asset\AssetAction` to the Di container.

From your view you can use as

```php
$this->url('hkt/psr7-asset:route', [
    'vendor' => 'vendor',
    'package' => 'package',
    'file' => '/css/bootstrap.min.css'
]);
```

This will return `/asset/vendor/package/css/bootstrap.min.css`.

## Mapping

With the help of mapping the `vendor/package` or directly the path you can alter the result it returns.

### Overriding css, js, images

Like [puli](https://github.com/puli) it is possible that you can override the style sheet, images, js etc for the downloaded package. You just need to map it. No magic under the hood.

## Configuration via Aura.Di

```php
<?php
namespace Hkt\Psr7Asset;

use Aura\Di\Container;
use Aura\Di\ContainerConfigInterface;

class AssetConfig implements ContainerConfigInterface
{
    public function define(Container $di)
    {
        // Choose one of the PSR-17 implementation
        // $di->params['Hkt\Psr7Asset\AssetResponder']['responseFactory'] = $di->lazyNew('Http\Factory\Diactoros\ResponseFactory');

        $di->set('Hkt\Psr7Asset\AssetLocator', $di->lazyNew('Hkt\Psr7Asset\AssetLocator'));

        $di->params['Hkt\Psr7Asset\AssetAction'] = array(
            'domain' => $di->lazyNew('Hkt\Psr7Asset\AssetService'),
            'responder' => $di->lazyNew('Hkt\Psr7Asset\AssetResponder'),
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
```
