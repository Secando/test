<?php

namespace App;

class Auth
{
    public function __construct(private readonly Session $session)
    {
    }

    public function login($user){
        $this->session->add('user',['id'=>$user->getId(),'email'=>$user->getEmail(),'created_at'=>$user->getCreatedAt(),'name'=>$user->getName()]);
    }

    public function check(){
        if($user = $this->session->get('user')){
            return $user;
        }
    }
    public function logout(){

        $this->session->remove('user');

    }
}