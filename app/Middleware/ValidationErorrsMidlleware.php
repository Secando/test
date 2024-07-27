<?php

namespace App\Middleware;

use     App\Exceptions\ValidationException;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Factory\ResponseFactory;
use Slim\Views\Twig;


class ValidationErorrsMidlleware implements MiddlewareInterface
{
    public function __construct(private readonly Twig $twig,private readonly ResponseFactoryInterface $response)
    {
    }

    public function process(Request $request, RequestHandler $handler): Response
    {
        try {
            return $handler->handle($request);

        }catch (ValidationException $exception){
            $_SESSION['old'] = $request->getParsedBody();
            $_SESSION['errors'] = array_map(fn($elem)=>$elem=array_pop($elem),$exception->errors);
            $referer = $request->getHeader('referer')[0];
            $response = $this->response->createResponse()->withStatus(302)->withHeader('Location', $referer);

            return $response;

        }
        return $handler->handle($request);


    }
}
