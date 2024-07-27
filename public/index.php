<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Csrf\Guard;
use Slim\Factory\AppFactory;


$container = require __DIR__ . '/../bootstrap.php';

$container->get(App::class)->run();
