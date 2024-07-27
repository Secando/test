<?php

namespace App\Middleware;

use App\Exceptions\ValidationException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Factory\ResponseFactory;
use Slim\Views\Twig;


class ValidationDataMidlleware implements MiddlewareInterface
{
    public function __construct(private readonly Twig $twig)
    {
    }

    public function process(Request $request, RequestHandler $handler): Response
    {
        if(isset($_SESSION['errors'])){
            $this->twig->getEnvironment()->addGlobal('errors',$_SESSION['errors']);
            unset($_SESSION['errors']);
        }
        return $handler->handle($request);

    }
}
