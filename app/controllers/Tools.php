<?php

namespace TodoList\Controllers;

class Tools{

    public static function sanitizer($val){

        $val = htmlentities($val, ENT_QUOTES);
        $val = trim($val);

        if(empty($val)){
            return false;
        }

        return $val;
    }

    public static function validateEmail($email){
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
            in_array(trim($sort_by), Post::$allowed_sort_fields)
        ){
            $query['sort_by'] = $sort_by;
        } elseif (
            isset($_GET['sort_by']) and
            !empty(trim($_GET['sort_by'])) and
            in_array(trim($_GET['sort_by']), Post::$allowed_sort_fields)
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

    public static function flash_message(){
        if(isset($_SESSION['messages']) and is_array($_SESSION['messages'])){
            foreach ($_SESSION['messages'] as $message){
                if($message['status'] == 'info'){
                    echo '<div class="alert alert-info" role="alert">'.$message['text'].'</div>';
                } else {
                    echo '<div class="alert alert-danger" role="alert">'.$message['text'].'</div>';
                }
            }
            unset($_SESSION['messages']);
        }
    }

}