# Monolog Extensions #

**Version:** *1.1.0*<br/>
**Master build:** [![Master branch build status][travis-master]][travis]<br/>
**Develop build:** [![Develop branch build status][travis-develop]][travis]

This library supplies additional log handlers, formatters and processors for use with [Monolog][monolog].
The intention is to make use of the library internally with the aim to eventually submit relevant parts to Monolog
core.<br/>
It can be installed in whichever way you prefer, but we recommend [Composer][packagist].
```json
{
    "require": {
        "graze/monolog-extensions": "~1.1.0"
    }
}
```

### Basic usage ###
```php
<?php
use Aws\DynamoDb\DynamoDbClient;
use Graze\Monolog\ErrorHandlerBuilder;
use Graze\Monolog\Handler\DynamoDbHandler;

$client  = DynamoDbClient::factory(array(/**config**/));
$builder = new ErrorHandlerBuilder();
$builder->setName('project-name')
        ->addHandler(new DynamoDbHandler($client, 'ErrorLog'));

$builder->buildAndRegister();
```


### Contributing ###
We accept contributions to the source via Pull Request,
but passing unit tests must be included before it will be considered for merge.
```bash
$ make
$ make tests
```

If you have [Vagrant][vagrant] installed, you can build our dev environment to assist development.
The repository will be mounted in `/srv`.
```bash
$ vagrant up
$ vagrant ssh

Welcome to Ubuntu 12.04 LTS (GNU/Linux 3.2.0-23-generic x86_64)
$ cd /srv
```


### License ###
The content of this library is released under the **MIT License** by **Nature Delivered Ltd**.<br/>
You can find a copy of this license at http://www.opensource.org/licenses/mit or in [`LICENSE`][license]


<!-- Links -->
[travis]: https://travis-ci.org/graze/MonologExtensions
[travis-master]: https://travis-ci.org/graze/MonologExtensions.png?branch=master
[travis-develop]: https://travis-ci.org/graze/MonologExtensions.png?branch=develop
[monolog]:   https://github.com/Seldaek/monolog
[packagist]: https://packagist.org/packages/graze/monolog-extensions
[vagrant]:   http://vagrantup.com
[license]:   /LICENSE
