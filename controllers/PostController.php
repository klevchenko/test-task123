<?php

require_once ROOT."/controllers/UserController.php";
require_once ROOT."/models/Post.php";

class PostController{

    public static $allowed_sort_fields = ['user_name', 'user_email', 'created_at', 'status'];
    public static $items_per_page = 3;

    public function index(){

        $postModel = new Post();
        $posts = [];

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
            require_once ROOT."/views/posts/index.php";
            die;
        }

        $total_pages = ceil($count / self::$items_per_page);

        if($page > $total_pages){
            $_SESSION['messages'][] = $this->newError('Page number cannot be bigger than '.$total_pages);
            header("Location: /");
            die();
        }

        $offset = ($page-1) * self::$items_per_page;

        $posts = $postModel->getAll($sort_by, $order, $offset, self::$items_per_page);

        require_once ROOT."/views/posts/index.php";
    }

    public function getOne($id){
        $postModel = new Post();

        $post = $postModel->getOne(intval($id));

        if(empty($post['id'])){
            $_SESSION['messages'][] = $this->newError('Wrong post id');
            header("Location: /");
            die();
        }

        $_SESSION['edit_post_id'] = $post['id'];

        require_once ROOT."/views/posts/edit.php";
    }

    public function store(){
        $postModel = new Post();

        $res = false;

        $user_name  = $this->sanitizer($_POST['user_name']);
        $user_email = $this->sanitizer($_POST['user_email']);
        $text       = $this->sanitizer($_POST['text']);

        if(!$user_name){
            $_SESSION['messages'][] = $this->newError('Invalid user name');
        }

        if(!$this->validateEmail($user_email)){
            $_SESSION['messages'][] = $this->newError('Invalid email address');
        }

        if(!$text){
            $_SESSION['messages'][] = $this->newError('Invalid text');
        }

        if(count($_SESSION['messages']) < 1){
            $res = $postModel->store($user_name, $user_email, $text);
        }

        if($res){
            $_SESSION['messages'][] = $this->newError('Task created', false);
            header("Location: /");
            die();
        }

        require_once ROOT."/views/posts/create.php";
    }

    public function update(){

        if( !UserController::loggedIn() ){
            $_SESSION['messages'][] = $this->newError('You must logged in');
            header("Location: /login");
            die();
        }

        $postModel = new Post();
        $res = false;

        $text           = $this->sanitizer($_POST['text']);
        $post_id        = intval($_SESSION['edit_post_id']);
        $task_completed = ( isset($_POST['task_completed']) and !empty($_POST['task_completed']) ) ? true : false;

        if(!$text){
            $_SESSION['messages'][] = $this->newError('Invalid text');
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
            $_SESSION['messages'][] = $this->newError('Nothing to update');
            header("Location: /post/edit/$post_id");
            die();
        }

        if(count($_SESSION['messages']) < 1){
            $res = $postModel->update($text, $post_id, $task_completed, $admin_edit);
            unset($_SESSION['edit_post_id']);
        }

        if($res){
            $_SESSION['messages'][] = $this->newError('Task updated', false);
            header("Location: /");
            die();
        } else {
            $_SESSION['messages'][] = $this->newError('Task update error');
            header("Location: /post/edit/$post_id");
            die();
        }

    }

    private function newError($text, $error = true){
        $class = $error ? 'alert-danger' : 'alert-success';
        return "<div class=\"alert $class my-3\" role=\"alert\">$text</div>";
    }

    private function sanitizer($val){

        $val = htmlentities($val, ENT_QUOTES);
        $val = trim($val);

        if(empty($val)){
            return false;
        }

        return $val;
    }

    private function validateEmail($email){
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return true;
        } else {
            return false;
        }
    }

    public static function createPaginateLink($page_number = 1, $sort_by = false, $order = false){

        $query = $_GET;

        $page_number = intval($page_number);

        if($page_number < 1){
            $page_number = 1;
        }

        $query['page'] = $page_number;

        if(
            !empty(trim($sort_by)) and
            in_array(trim($sort_by), self::$allowed_sort_fields)
        ){
            $query['sort_by'] = $sort_by;
        } elseif (
            isset($_GET['sort_by']) and
            !empty(trim($_GET['sort_by'])) and
            in_array(trim($_GET['sort_by']), self::$allowed_sort_fields)
        ) {
            $query['sort_by'] = trim(strval($_GET['sort_by']));
        } else {
            $query['sort_by'] = 'created_at';
        }

        if($order === 'DESC' || $order === 'ASC'){
            $query['order'] = $order;
        } else {
            $query['order'] = ( isset($_GET['order']) and trim($_GET['order']) === 'DESC')  ? 'DESC' : 'ASC';
        }

        return "?" . http_build_query($query);
    }

}