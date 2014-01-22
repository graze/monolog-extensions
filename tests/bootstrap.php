<?php

$loader = require dirname(__DIR__) . '/vendor/autoload.php';
$loader->add('Graze\Monolog\Test', dirname(__DIR__) . '/tests/lib');
$loader->add('Monolog', dirname(__DIR__) . '/vendor/monolog/monolog/tests');
