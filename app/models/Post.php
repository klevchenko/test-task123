<?php

namespace TodoList\Models;

use TodoList\DB;

class Post{

    public function getAll($sort_by, $order, $offset, $items_per_page){
        $db = DB::connect();
        $posts = [];

        $stmt = $db->query("SELECT * FROM posts ORDER BY $sort_by $order LIMIT $offset , $items_per_page");
        while ($row = $stmt->fetch())
        {
            $posts[] = [
                "id"         => $row['id'],
                "user_name"  => $row['user_name'],
                "user_email" => $row['user_email'],
                "text"       => $row['text'],
                "status"     => $row['status'],
                "admin_edit" => $row['admin_edit'],
            ];
        }

        return $posts;
    }

    public function getOne($id){
        $db = DB::connect();
        $post = [];

        $stmt = $db->prepare("SELECT * FROM posts WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        while ($row = $stmt->fetch())
        {
            $post = [
                "id"         => $row['id'],
                "user_name"  => $row['user_name'],
                "user_email" => $row['user_email'],
                "text"       => $row['text'],
                "status"     => $row['status'],
                "admin_edit" => $row['admin_edit'],
            ];
        }

        return $post;
    }

    public function getTotalPosts(){
        $db = DB::connect();

        $count = $db->query('SELECT COUNT(*) FROM posts')->fetchColumn();

        return $count;
    }

    public function store($user_name, $user_email, $text){
        $db = DB::connect();

        $stmt = $db->prepare("INSERT INTO posts (user_name, user_email, text) VALUES (:user_name, :user_email, :text)");
        $stmt->bindParam(':user_name', $user_name);
        $stmt->bindParam(':user_email', $user_email);
        $stmt->bindParam(':text', $text);

        return $stmt->execute();
    }

    public function update($text, $post_id, $task_completed, $admin_edit){
        $db = DB::connect();

        $data = [
            'text' => $text,
            'status' => $task_completed,
            'admin_edit' => $admin_edit,
            'id' => $post_id,
        ];

        $sql  = "UPDATE posts SET text=:text, status=:status, admin_edit=:admin_edit WHERE id=:id";
        $stmt = $db->prepare($sql);

        return $stmt->execute($data);
    }

}
