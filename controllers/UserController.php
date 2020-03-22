<?php

class UserController{

    private $login = 'admin';
    private $pass  = '123';

    public function login(){

        $login = $this->sanitizer($_POST['user_name']) ? $this->sanitizer($_POST['user_name']) : false;
        $pass  = $this->sanitizer($_POST['password'])  ? $this->sanitizer($_POST['password'])  : false;

        if($login !== $this->login){
            $_SESSION['messages'][] = $this->newError('Login failed!');
            header("Location: /login");
            die();
        }

        if($pass !== $this->pass){
            $_SESSION['messages'][] = $this->newError('Login failed!');
            header("Location: /login");
            die();
        }

        $_SESSION['ifLogedIn'] = true;
        header("Location: /");
        die();

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
        session_destroy();
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
