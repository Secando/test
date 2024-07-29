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
    public function vacations(RequestInterface $request ,ResponseInterface $response,$args)
    {

        return $this->twig->render($response, 'vacations.twig');
    }
    public function delivery(RequestInterface $request ,ResponseInterface $response,$args)
    {

        return $this->twig->render($response, 'delivery.twig');
    }
}