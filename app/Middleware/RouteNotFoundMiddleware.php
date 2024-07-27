<?php

namespace App\Middleware;

use     App\Exceptions\ValidationException;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Exception\HttpNotFoundException;
use Slim\Psr7\Factory\ResponseFactory;
use Slim\Views\Twig;


class RouteNotFoundMiddleware implements MiddlewareInterface
{
    public function __construct(private readonly Twig $twig,private readonly ResponseFactoryInterface $response)
    {
    }

    public function process(Request $request, RequestHandler $handler): Response
    {
        try {
            return $handler->handle($request);
        }catch (HttpNotFoundException $exception){
            $response = $this->response->createResponse()->withStatus(404);
            return $this->twig->render($response,'404.twig');

        }
        return $handler->handle($request);


    }
}
