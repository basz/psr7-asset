# Psr7Asset

Asset management for PHP. This package is a fork of [Aura.Asset_Bundle](https://github.com/friendsofaura/Aura.Asset_Bundle)

## Todo

* Fix the broken test
* Add symbolic link ?
* If symbolic linked, way to remove them via cli

## Foreword

### Requirements

This package has some userland dependencies:

- [psr/http-message-implementation](https://packagist.org/providers/psr/http-message-implementation)
- [psr/http-factory](https://github.com/php-fig/fig-standards/blob/master/proposed/http-factory/http-factory.md) (WIP)

### Installation

```
composer require hkt/psr7-asset
```

### Tests

[![Build Status](https://travis-ci.org/harikt/Psr7Asset.png?branch=master)](https://travis-ci.org/harikt/Psr7Asset)

```bash
composer install
phpunit -c tests/unit
```

### PSR Compliance

This attempts to comply with [PSR-1][], [PSR-2][], [PSR-4][] and [PSR-7][]. If
you notice compliance oversights, please send a patch via pull request.

[PSR-1]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-1-basic-coding-standard.md
[PSR-2]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md
[PSR-4]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-4-autoloader.md

[PSR-7]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-7-http-message.md

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

If you are using `zend expressive` it is as below,

```php
$this->url('hkt/psr7-asset:route', [
    'vendor' => '',
    'package' => '',
    'file' => '/web/css/bootstrap.min.css'
]);
``

## Mapping

With the help of mapping the `vendor/package` or directly the path you can alter the result it returns.

Eg configuration in Aura.Di.

```php
$di->params['Hkt\Psr7Asset\AssetService']['map'] = [
    // 'vendor/package/css/hello.css' => dirname(dirname(__DIR__)) . '/asset/css/test.css',
    'vendor/package' => dirname(dirname(__DIR__)) . '/asset',
];

$di->params['Hkt\Psr7Asset\AssetResponder']['streamFactory'] = $di->lazyNew('Http\Factory\Diactoros\StreamFactory');
```

## Current Dependency

For the current time you need to modify `composer.json` to something like.. Probably more configuration which is missed to document. Eventually there will be less configuration.

```json
{
    // ..
    "repositories": [
        {
            "type": "git",
            "url": "https://github.com/shadowhand/http-factory.git"
        },
        {
            "type": "package",
            "package": {
                "name": "zendframework/http-factory-diactoros",
                "version": "1.0",
                "source": {
                    "type": "git",
                    "url": "https://github.com/shadowhand/http-factory-diactoros.git",
                    "reference": "master"
                },
                "autoload": {
                    "psr-4": {
                        "Http\\Factory\\Diactoros\\": "src/"
                    }
                }
            }
        },
        {
            "type": "git",
            "url": "https://github.com/harikt/psr7-asset.git"
        },
    ],
    "require": {
        //....
        "hkt/psr7-asset": "1.*@dev",
        "psr/http-factory": "1.*@dev",        
        "zendframework/http-factory-diactoros": "1.*"
    }
}
```
