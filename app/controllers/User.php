<?php

namespace TodoList\Controllers;

class User{

    private $login = 'admin';
    private $pass  = '123';

    public function login(){

        $login = $this->sanitizer($_POST['user_name']) ? $this->sanitizer($_POST['user_name']) : false;
        $pass  = $this->sanitizer($_POST['password'])  ? $this->sanitizer($_POST['password'])  : false;

        unset($_SESSION['messages']);

        if(empty($login)){
            $_SESSION['messages'][] = $this->newError('Login cannot be blank');
            header("Location: /login");
            die();
        }

        if(empty($pass)){
            $_SESSION['messages'][] = $this->newError('Password cannot be blank');
            header("Location: /login");
            die();
        }

        if(
            empty($_SESSION['messages']) and
            $login === $this->login and
            $pass === $this->pass
        ){
            $_SESSION['ifLogedIn'] = true;
            $_SESSION['messages'][] = $this->newError('You are logged in', false);
            header("Location: /");
            die();
        } else {
            $_SESSION['messages'][] = $this->newError('Login failed!');
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
        $_SESSION['messages'][] = $this->newError('You are logged out');
        header("Location: /");
        die();
    }

    public function sanitizer($val){

        $val = htmlentities($val, ENT_QUOTES);
        $val = trim($val);
        $val = strval($val);

        if(empty($val)){
            return false;
        }

        return $val;
    }

    private function newError($text, $error = true){
        $class = $error ? 'alert-danger' : 'alert-success';
        return "<div class=\"alert $class my-3\" role=\"alert\">$text</div>";
    }

}
