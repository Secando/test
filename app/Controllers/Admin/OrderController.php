<?php

namespace App\Controllers\Admin;

use App\Services\OrderService;
use App\Services\ProductService;
use App\Session;
use app\Validations\ProductValidation;
use Doctrine\ORM\EntityManager;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;

class OrderController
{
    public function __construct(
        private EntityManager $em,
        private Twig $twig,
        private OrderService $orderService,
        private readonly Session $session
    ) {
    }

    public function index(Request $request, Response $response, $args)
    {
        $orders = $this->orderService->getAllForAdmin();
        return $this->twig->render($response, 'admin/orders_index.twig', ['orders' => $orders]);
    }

    public function update(Request $request, Response $response, $args)
    {
        $data = $request->getParsedBody();
        $orders = $this->orderService->updateStatus($data);

        return $response->withStatus(302)->withHeader('Location', $request->getHeader('referer'));
    }
    public function delete(Request $request, Response $response, $args)
    {
        $data = $request->getParsedBody();
        $orders = $this->orderService->delete($data);

        return $response->withStatus(302)->withHeader('Location', $request->getHeader('referer'));
    }
}