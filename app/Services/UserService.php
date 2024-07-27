<?php

namespace App\Services;

use App\Auth;
use App\Entity\User;
use App\Exceptions\ValidationException;
use App\Session;
use app\Validations\UserChangePasswordValidation;
use Doctrine\ORM\EntityManager;

class UserService
{
    public function __construct(private EntityManager $em,private readonly Auth $auth,private readonly Session $session)
    {
    }

    public function register($data){
        $user = new User();
        $user->setName($data['name']);
        $user->setEmail($data['email']);
        $password = password_hash($data['password'],PASSWORD_BCRYPT);
        $user->setPassword($password);
        $this->em->persist($user);
        $this->em->flush();
        return $user;
    }

    public function login( $data)
    {

        $user = $this->em->getRepository(User::class)->findOneBy(['email'=>$data['email']]);
        if(!$user){
            throw new ValidationException(['login'=>['login'=>'Неверный логин или пароль']]);

        }

        if(!password_verify($data['password'],$user->getPassword())){
            throw new ValidationException(['login'=>['login'=>'Неверный логин или пароль']]);
        }
        $this->auth->login($user);
    }

    public function changePassword($data)
    {

        $userId = $this->auth->check()['id'];
        $user = $this->em->getRepository(User::class)->findOneBy(['id'=>$userId]);

        if(password_verify($data['old_password'],$user->getPassword())){
            $user->setPassword(password_hash($data['new_password'],PASSWORD_BCRYPT));
            $this->em->persist($user);
            $this->em->flush();
    }

    }

    public function getAll()
    {
        $users = $this->em->getRepository(User::class)->findAll();
        return $users;


    }
}