<?php

namespace App\Controllers;
use Doctrine\ORM\EntityManager;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Views\Twig;

class IndexController
{
    public function __construct(private EntityManager $em, private Twig $twig)
    {
    }

    public function about(RequestInterface $request ,ResponseInterface $response,$args)
    {

        return $this->twig->render($response, 'about.twig');
    }
}