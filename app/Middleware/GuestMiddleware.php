<?php

namespace App\Middleware;

use App\Exceptions\ValidationException;
use App\Session;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Factory\ResponseFactory;
use Slim\Views\Twig;


class GuestMiddleware implements MiddlewareInterface
{
    public function __construct(
        private readonly Twig $twig,
        private readonly Session $session,
        private readonly ResponseFactoryInterface $response
    ) {
    }

    public function process(Request $request, RequestHandler $handler): Response
    {
        if (!$this->session->get('user')) {
            return $this->response->createResponse()->withStatus(302)->withHeader('Location', '/register');

        }
        return $handler->handle($request);
    }
}
