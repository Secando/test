<?php

namespace App\Controllers;

use App\Auth;
use App\CustomMailer;
use App\Entity\User;
use App\Exceptions\ValidationException;
use App\Services\EmailService;
use App\Services\ReCaptchaService;
use App\Services\UserService;
use App\Validations\UserChangePasswordValidation;
use App\Validations\UserRegisterValidation;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Respect\Validation\Validator as v;
use Slim\Views\Twig;

class UserController
{
    public function __construct(
        private readonly Twig $twig,
        private readonly ReCaptchaService $reCaptcha,
        private readonly UserRegisterValidation $userRegisterValidation,
        private readonly UserService $userService,
        private readonly EmailService $emailService,
        private readonly Auth $auth,
        private readonly UserChangePasswordValidation $userChangePasswordValidation
    ) {
    }

    public function showLoginForm(RequestInterface $request, ResponseInterface $response, $args)
    {


        return $this->twig->render($response, 'login.twig');
    }

    public function showProfile(RequestInterface $request, ResponseInterface $response, $args)
    {


        return $this->twig->render($response, 'profile.twig');
    }

    public function showRegisterForm(RequestInterface $request, ResponseInterface $response, $args)
    {

        return $this->twig->render($response, 'register.twig');
    }

    public function login(Request $request, Response $response, $args)
    {
        $user = $request->getParsedBody();

        $user = $this->userService->login($user);
//
        return $response->withStatus(302)->withHeader('Location', '/catalog');
    }

    public function logout(Request $request, Response $response, $args)
    {

        $this->auth->logout();
        return $response->withStatus(302)->withHeader('Location', '/catalog');
    }

    public function register(Request $request, Response $response, $args)
    {
        $data = $request->getParsedBody();
//        if(!$this->reCaptcha->check($data)->success){
//            throw new \Exception();
//        }
        unset($data['g-recaptcha']);

        $this->userRegisterValidation->validate($data);

        $user = $this->userService->register($data);
        $this->emailService->sendVerificationLink($user);

        return $response->withStatus(302)->withHeader('Location', '/verification');
    }

    public function changePassword(Request $request, Response $response, $args){
        $data = $request->getParsedBody();

        $this->userChangePasswordValidation->validate($data);
        $this->userService->changePassword($data);
        return $response->withStatus(302)->withHeader('Location', '/profile');
    }

}