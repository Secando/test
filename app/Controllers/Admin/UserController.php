<?php

namespace App\Controllers\Admin;

use App\Services\ProductService;
use App\Services\UserService;
use App\Validations\ProductValidation;
use Doctrine\ORM\EntityManager;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;

class UserController
{
    public function __construct(
        private EntityManager $em,
        private Twig $twig,
        private UserService $userService

    ) {
    }

    public function index(Request $request, Response $response, $args)
    {

        $users = $this->userService->getAll();
        return $this->twig->render($response, 'admin/user_index.twig',['users'=>$users]);
    }



}