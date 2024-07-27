<?php

namespace App\Controllers;

use App\Services\CartService;
use App\Services\OrderService;
use App\Services\ProductService;
use App\Session;
use Doctrine\ORM\EntityManager;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;

class OrderController
{
    public function __construct(private EntityManager $em, private Twig $twig, private OrderService $orderService, private readonly Session $session)
    {
    }

    public function index(Request $request, Response $response, $args)
    {
        $orders = $this->orderService->getAll();
        return $this->twig->render($response, 'order.twig', ['orders' => $orders]);


    }
    public function makeOrder(Request $request, Response $response, $args)
    {
        $this->orderService->createOrder();

        return $response->withStatus(302)->withHeader('Location', $request->getHeader('referer'));

    }



}