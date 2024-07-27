<?php


use Slim\App;

return function (App $app) {
    $container = $app->getContainer();
    $app->add(\App\Middleware\ValidationErorrsMidlleware::class);

    $app->add(\App\Middleware\ValidationDataMidlleware::class);
    $app->add(\App\Middleware\OldDataMidlleware::class);
    $app->add(\App\Middleware\UserMiddleware::class);
    $app->add(\App\Middleware\RouteNotFoundMiddleware::class);
//    $app->add(\App\Middleware\CsrfFieldsMiddleware::class);
//    $app->add('csrf');

};