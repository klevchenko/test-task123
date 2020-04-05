<?php

namespace TodoList\Controllers;

class User{

    private $login = 'admin';
    private $pass  = '123';

    public function login(){

        $login = Tools::sanitizer($_POST['user_name']) ? Tools::sanitizer($_POST['user_name']) : false;
        $pass  = Tools::sanitizer($_POST['password'])  ? Tools::sanitizer($_POST['password'])  : false;

        unset($_SESSION['messages']);

        if(empty($login)){
            $_SESSION['messages'][] = [
                "status" => "error",
                "text"   => 'Login cannot be blank',
            ];
            header("Location: /login");
            die();
        }

        if(empty($pass)){
            $_SESSION['messages'][] = [
                "status" => "error",
                "text"   => 'Password cannot be blank',
            ];
            header("Location: /login");
            die();
        }

        if(
            empty($_SESSION['messages']) and
            $login === $this->login and
            $pass === $this->pass
        ){
            $_SESSION['ifLogedIn'] = true;
            $_SESSION['messages'][] = [
                "status" => "info",
                "text"   => 'You are logged in',
            ];
            header("Location: /");
            die();
        } else {
            $_SESSION['messages'][] = [
                "status" => "error",
                "text"   => 'Login failed!',
            ];
            header("Location: /login");
            die();
        }
    }

    public static function loggedIn(){
        if($_SESSION['ifLogedIn']){
            return true;
        } else {
            return false;
        }
    }

    public function logout(){
        unset($_SESSION['ifLogedIn']);
        $_SESSION['messages'][] = [
            "status" => "info",
            "text"   => 'You are logged out',
        ];
        header("Location: /");
        die();
    }

}
