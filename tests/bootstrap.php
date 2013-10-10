<?php

$loader = require dirname(__DIR__) . '/vendor/autoload.php';
$loader->add('Graze\\Monolog\\Test', __DIR__ . '/lib');
$loader->add('Monolog', dirname(__DIR__) . '/vendor/monolog/monolog/tests');
