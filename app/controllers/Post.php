<?php

namespace TodoList\Controllers;

class Post{

    public static $allowed_sort_fields = ['user_name', 'user_email', 'created_at', 'status'];
    public static $items_per_page = 3;

    public function index(){

        $postModel = new \TodoList\Models\Post();

        if (
            isset($_GET['page']) and
            intval($_GET['page']) > 1
        ) {
            $page = intval($_GET['page']);
        } else {
            $page = 1;
        }

        if (
            isset($_GET['sort_by']) and
            !empty(trim($_GET['sort_by'])) and
            in_array(trim($_GET['sort_by']), self::$allowed_sort_fields)
        ) {
            $sort_by = trim(strval($_GET['sort_by']));
        } else {
            $sort_by = 'created_at';
        }

        $order = (isset($_GET['order']) and trim(strval($_GET['order'])) === 'ASC' ) ? 'ASC' : 'DESC';

        $count = $postModel->getTotalPosts();

        if($count == 0){
            require_once APP_ROOT."/views/posts/index.php";
            die;
        }

        $total_pages = ceil($count / self::$items_per_page);

        if($page > $total_pages){
            $_SESSION['messages'][] = [
                "status" => "error",
                "text"   => 'Page number cannot be bigger than '.$total_pages,
            ];
            header("Location: /");
            die();
        }

        $offset = ($page-1) * self::$items_per_page;

        $posts = $postModel->getAll($sort_by, $order, $offset, self::$items_per_page);

        require_once APP_ROOT."/views/posts/index.php";
    }

    public function getOne($id){
        $postModel = new \TodoList\Models\Post();

        $post = $postModel->getOne(intval($id));

        if(empty($post['id'])){
            $_SESSION['messages'][] = [
                "status" => "error",
                "text"   => 'Wrong post id',
            ];
            header("Location: /");
            die();
        }

        $_SESSION['edit_post_id'] = $post['id'];

        require_once APP_ROOT."/views/posts/edit.php";
    }

    public function store(){
        $postModel = new \TodoList\Models\Post();

        $res = false;

        $user_name  = Tools::sanitizer($_POST['user_name']);
        $user_email = Tools::sanitizer($_POST['user_email']);
        $text       = Tools::sanitizer($_POST['text']);

        if(!$user_name){
            $_SESSION['messages'][] = [
                "status" => "error",
                "text"   => 'Invalid user name',
            ];
        }

        if(!Tools::validateEmail($user_email)){
            $_SESSION['messages'][] = [
                "status" => "error",
                "text"   => 'Invalid email address',
            ];
        }

        if(!$text){
            $_SESSION['messages'][] = [
                "status" => "error",
                "text"   => 'Invalid text',
            ];
        }

        if(count($_SESSION['messages']) < 1){
            $res = $postModel->store($user_name, $user_email, $text);
        }

        if($res){
            $_SESSION['messages'][] = [
                "status" => "info",
                "text"   => 'Task created',
            ];
            header("Location: /");
            die();
        }

        require_once APP_ROOT."/views/posts/create.php";
    }

    public function update(){

        if( !User::loggedIn() ){
            $_SESSION['messages'][] = [
                "status" => "error",
                "text"   => 'You must logged in',
            ];
            header("Location: /login");
            die();
        }

        $postModel = new \TodoList\Models\Post();
        $res = false;

        $text           = Tools::sanitizer($_POST['text']);
        $post_id        = intval($_SESSION['edit_post_id']);
        $task_completed = ( isset($_POST['task_completed']) and !empty($_POST['task_completed']) ) ? true : false;

        if(!$text){
            $_SESSION['messages'][] = [
                "status" => "error",
                "text"   => 'Invalid text',
            ];
        }

        $old_data = $postModel->getOne($post_id);

        $old_text       = $old_data["text"];
        $old_admin_edit = $old_data["admin_edit"];
        $old_status     = $old_data["status"];

        if($old_admin_edit == 1 || strval($old_text) != strval($text)){
            $admin_edit = true;
        } else {
            $admin_edit = false;
        }

        if(
            strval($old_text) == strval($text) and
            $old_admin_edit   == $admin_edit and
            $old_status       == $task_completed
        ){
            $_SESSION['messages'][] = [
                "status" => "error",
                "text"   => 'Nothing to update',
            ];
            header("Location: /post/edit/$post_id");
            die();
        }

        if(count($_SESSION['messages']) < 1){
            $res = $postModel->update($text, $post_id, $task_completed, $admin_edit);
            unset($_SESSION['edit_post_id']);
        }

        if($res){
            $_SESSION['messages'][] = [
                "status" => "info",
                "text"   => 'Task updated',
            ];
            header("Location: /");
            die();
        } else {
            $_SESSION['messages'][] = [
                "status" => "error",
                "text"   => 'Task update error',
            ];
            header("Location: /post/edit/$post_id");
            die();
        }

    }
}