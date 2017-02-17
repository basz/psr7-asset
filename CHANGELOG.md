# 0.2.0

## Added

* `AssetLocator` class added to add / manipulate asset names and location
* Exception `PathNotFound` added.
* Added phpunit as require-dev dependency.
* Added CallbackStream which is copied from https://github.com/zendframework/zend-diactoros/blob/83e8d98b9915de76c659ce27d683c02a0f99fa90/src/CallbackStream.php
* Added https://github.com/http-interop/http-factory, so can work with any psr-7 implementations.

## BC BREAK

* `AssetService` now accepts `AssetLocator` instead of array as first constructor argument

# 0.1

Initial Release
