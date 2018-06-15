# Monolog Extensions #

[![Latest Version on Packagist](https://img.shields.io/packagist/v/graze/monolog-extensions.svg?style=flat-square)](https://packagist.org/packages/graze/monolog-extensions)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/graze/monolog-extensions/master.svg?style=flat-square)](https://travis-ci.org/graze/monolog-extensions)
[![Coverage Status](https://img.shields.io/scrutinizer/coverage/g/graze/monolog-extensions.svg?style=flat-square)](https://scrutinizer-ci.com/g/graze/monolog-extensions/code-structure)
[![Quality Score](https://img.shields.io/scrutinizer/g/graze/monolog-extensions.svg?style=flat-square)](https://scrutinizer-ci.com/g/graze/monolog-extensions)
[![Total Downloads](https://img.shields.io/packagist/dt/graze/monolog-extensions.svg?style=flat-square)](https://packagist.org/packages/graze/monolog-extensions)

This library supplies additional log handlers, formatters and processors for use with [Monolog][monolog].
The intention is to make use of the library internally with the aim to eventually submit relevant parts to Monolog
core.

It can be installed in whichever way you prefer, but we recommend [Composer][packagist].

```bash
$ composer require graze/monolog-extensions
```

### ErrorHandlerBuilder usage
```php
<?php
use Aws\DynamoDb\DynamoDbClient;
use Graze\Monolog\ErrorHandlerBuilder;

$builder = new ErrorHandlerBuilder();
$builder->setName('project-name')
        ->addHandler(/**$handler**/);

$builder->buildAndRegister();
```

### RaygunHandler Usage
```php
<?php
use Graze\Monolog\Handler\RaygunHandler;
use Monolog\Logger;
use Raygun4php\RaygunClient;

// Create the client, using the Raygun SDK
$client = new RaygunClient('api-key');

// Create the handler
$handler = new RaygunHandler($client);

// Create the logger
$logger = new Logger('project-name', array($handler));
```

## Contributing
We accept contributions to the source via Pull Request,
but passing unit tests must be included before it will be considered for merge.
```bash
$ make
$ make test
```

### License
The content of this library is released under the **MIT License** by **Nature Delivered Ltd**.<br/>

<!-- Links -->
[travis]: https://travis-ci.org/graze/MonologExtensions
[travis-master]: https://travis-ci.org/graze/MonologExtensions.png?branch=master
[monolog]:   https://github.com/Seldaek/monolog
[packagist]: https://packagist.org/packages/graze/monolog-extensions
[license]:   /LICENSE
