<?php

namespace App\Controllers;

use App\Services\CartService;
use App\Services\ProductService;
use App\Session;
use Doctrine\ORM\EntityManager;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;

class CartController
{
    public function __construct(private EntityManager $em, private Twig $twig, private CartService $cartService, private readonly Session $session)
    {
    }

    public function index(Request $request, Response $response, $args)
    {
        $products = $this->cartService->getAll();
        return $this->twig->render($response,'checkout.twig',['products'=>$products]);
    }
    public function addToCart(Request $request, Response $response, $args)
    {
        if(!key_exists('id',$request->getParsedBody())){
            return $response->withStatus(302)->withHeader('Location', $request->getHeader('referer'));
        }

        $id = (int) $request->getParsedBody()['id'];
        $this->cartService->addToCart($id);

        return $response->withStatus(302)->withHeader('Location', $request->getHeader('referer'));

    }
    public function deleteFromCart(Request $request, Response $response, $args)
    {
        $id = $args['id'];
        $this->cartService->deleteFromCart($id);
        return $response->withStatus(302)->withHeader('Location', $request->getHeader('referer'));
    }



}