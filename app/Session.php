<?php

namespace App;

class Session
{
    public function startSession()
    {
        session_start();
    }

    public function add($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    public function get($key)
    {
        if (key_exists($key, $_SESSION)) {
            return $_SESSION[$key];
        }
        return false;
    }

    public function remove($key)
    {

        unset($_SESSION[$key]);

    }
    public function removeFromArr($key,$value)
    {
        unset($_SESSION[$key][array_flip($_SESSION[$key])[$value]]);

    }
    public function push($key,$value){
        if(!key_exists($key,$_SESSION)){
            $_SESSION[$key] = [$value];
            return;
        }
        array_push($_SESSION[$key],$value);
    }

    public function has($key)
    {
        if(key_exists($key,$_SESSION)){
            return true;
        }
        return false;

    }

}