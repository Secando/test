<?php

namespace App\Middleware;

use App\Exceptions\ValidationException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Factory\ResponseFactory;
use Slim\Views\Twig;


class OldDataMidlleware implements MiddlewareInterface
{
    public function __construct(private readonly Twig $twig)
    {
    }

    public function process(Request $request, RequestHandler $handler): Response
    {
        if(isset($_SESSION['old'])){
            $this->twig->getEnvironment()->addGlobal('old',$_SESSION['old']);
            unset($_SESSION['old']);
        }
        $response = $handler->handle($request);

        return $response;
    }
}
