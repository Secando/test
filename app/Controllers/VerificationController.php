<?php

namespace App\Controllers;
use App\Auth;
use App\Entity\User;
use App\Entity\UserCode;
use App\Enums\UserCodeStatus;
use Doctrine\ORM\EntityManager;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Respect\Validation\Rules\DateTime;
use Slim\Views\Twig;

class VerificationController
{
    public function __construct(private EntityManager $em, private Twig $twig,private Auth $auth)
    {
    }

    public function index(RequestInterface $request ,ResponseInterface $response,$args)
    {

        return $this->twig->render($response, 'verification.twig');
    }
    public function verificate(RequestInterface $request ,ResponseInterface $response,$args){

        $code = $this->em->getRepository(UserCode::class)->findOneBy(['code'=>$code = $args['code']]);
        if(!$code){
            return  $response->withStatus(302)->withHeader('Location', '/register');
        }
        $code->setStatus(UserCodeStatus::SUCCESS->value);
        $user = $this->em->getRepository(User::class)->find($code->getUserId());
        $user->setVerificatedAt((new \DateTime())->format('Y-m-d H:i:s'));
        $this->em->persist($user);
        $this->em->persist($code);
        $this->em->flush();
        $this->auth->login($user);
        return $response->withStatus(302)->withHeader('Location', '/profile');
    }
}