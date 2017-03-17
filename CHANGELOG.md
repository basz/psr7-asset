# 0.2.0

## Added

* `AssetLocator` class added to add / manipulate asset names and location
* Exception `PathNotFound` added.
* Added phpunit as require-dev dependency.
* Added CallbackStream which is copied from https://github.com/zendframework/zend-diactoros/blob/83e8d98b9915de76c659ce27d683c02a0f99fa90/src/CallbackStream.php
* Added https://github.com/http-interop/http-factory, so can work with any psr-7 implementations.
* Added Router class which can be configured with regx which can be injected to AssetAction if needed.
* Added `Hkt\Psr7Asset\Container\AssetConfig` class and removed the example class.
* Updated Readme

## BC BREAK

* `AssetService` now accepts `AssetLocator` instead of array as first constructor argument
* Removed `setRouteRegx` method from `AssetAction`
* If file is not found, the 404 response is no longer send, delegates to next middleware or the final handler.

# 0.1

Initial Release
