<?php


use Slim\Factory\AppFactory;

session_start();
require __DIR__ . '/vendor/autoload.php';

require __DIR__ .'/app/configs/definitions.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__.'/');
$dotenv->load();
return require CONFIG_PATH . '/container.php';
