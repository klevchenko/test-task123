<?php

class BD{

    public static function connect(){
        $params = require('db-config.php');

        $dsn = "mysql:host={$params['host']};dbname={$params['dbname']}";
        $db = new PDO($dsn, $params['user'], $params['pass']);
        $db->exec('SET CHARACTER SET utf8');

        return $db;
    }

}
