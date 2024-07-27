<?php


use App\Middleware\AuthMiddleware;
use Slim\App;

return function (App $app){
    $container = $app->getContainer();
    $app->group('',function ($group){
        $group->get('/login', [\App\Controllers\UserController::class, 'showLoginForm']);
        $group->post('/login', [\App\Controllers\UserController::class, 'login']);
        $group->get('/register', [\App\Controllers\UserController::class, 'showRegisterForm']);
        $group->post('/register', [\App\Controllers\UserController::class, 'register']);
        $group->get('/verificate', [\App\Controllers\VerificationController::class, 'index']);
        $group->get('/verificate/{code}', [\App\Controllers\VerificationController::class,'verificate']);



    })->add(AuthMiddleware::class);
    $app->post('/logout', [\App\Controllers\UserController::class,'logout']);
    $app->group('',function ($group) {
        $group->get('/profile', [\App\Controllers\UserController::class, 'showProfile']);
        $group->post('/profile/changepass', [\App\Controllers\UserController::class, 'changePassword']);
        $group->post('/catalog/cart', [\App\Controllers\CartController::class, 'addToCart']);
        $group->get('/cart', [\App\Controllers\CartController::class, 'index']);
        $group->get('/cart/delete/{id}', [\App\Controllers\CartController::class, 'deleteFromCart']);
        $group->post('/order', [\App\Controllers\OrderController::class, 'makeOrder']);
        $group->post('/product/favorite', [\App\Controllers\FavoriteController::class, 'addFavorite']);
        $group->get('/favorite', [\App\Controllers\FavoriteController::class, 'index']);
        $group->get('/orders', [\App\Controllers\OrderController::class, 'index']);
    })->add(\App\Middleware\GuestMiddleware::class);

    $app->get('/catalog/[params]', [\App\Controllers\CatalogController::class, 'filter'])->add(
        \App\Middleware\FilterMiddleware::class
    );
    $app->get('/catalog/{name}', [\App\Controllers\CatalogController::class, 'showProduct']);
    $app->get('/catalog', [\App\Controllers\CatalogController::class, 'index']);
    $app->get('/', [\App\Controllers\IndexController::class, 'about']);

    $app->group('/admin',function ($group){
        $group->get('/products/create', [\App\Controllers\Admin\ProductController::class, 'create']);
        $group->get('/', [\App\Controllers\Admin\ProductController::class, 'create']);
        $group->post('/products/create', [\App\Controllers\Admin\ProductController::class, 'store']);
        $group->get('/products', [\App\Controllers\Admin\ProductController::class, 'index']);
        $group->get('/products/edit/{id}', [\App\Controllers\Admin\ProductController::class, 'edit']);
        $group->post('/products/edit/{id}', [\App\Controllers\Admin\ProductController::class, 'update']);
        $group->post('/products/delete/{id}', [\App\Controllers\Admin\ProductController::class, 'delete']);
        $group->get('/users', [\App\Controllers\Admin\UserController::class, 'index']);
        $group->get('/orders', [\App\Controllers\Admin\OrderController::class, 'index']);
        $group->post('/orders/update', [\App\Controllers\Admin\OrderController::class, 'update']);
        $group->post('/orders/delete', [\App\Controllers\Admin\OrderController::class, 'delete']);
    });

};
