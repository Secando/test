<?php

namespace App\Controllers;

use App\Services\CartService;
use App\Services\FavoriteService;
use App\Services\OrderService;
use App\Services\ProductService;
use App\Session;
use Doctrine\ORM\EntityManager;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;

class FavoriteController
{
    public function __construct(private EntityManager $em, private Twig $twig, private FavoriteService $favoriteService, private readonly Session $session)
    {
    }

    public function index(Request $request, Response $response, $args)
    {
        $favorites = $this->favoriteService->getFavorite();
        return $this->twig->render($response, 'favorites.twig', ['favorites' => $favorites]);

    }
    public function addFavorite(Request $request, Response $response, $args)
    {
        $id = $request->getParsedBody()['id'];
        $this->favoriteService->add($id);
        return $response->withStatus(302)->withHeader('Location', $request->getHeader('referer'));

    }



}