<?php

use \TodoList\Controllers as Controllers;

// get and show all posts
$klein->respond('GET', '/', function () {
    $controller = new Controllers\Post();
    $controller->index();
    exit;
});

// show new post form
$klein->respond('GET', '/post/create', function () {
    require_once APP_ROOT.'/views/posts/create.php';
    exit;
});

// new post form
$klein->respond('POST', '/post/create', function () {
    $controller = new Controllers\Post();
    $controller->store();
});

// show edit post form
$klein->respond('get', '/post/edit/[:id]', function ($request) {
    $controller = new Controllers\Post();
    $controller->getOne($request->id);
});

// update post
$klein->respond('POST', '/post/update', function () {
    $controller = new Controllers\Post();
    $controller->update();
});

// login/logout routes
$klein->respond('POST', '/login', function () {
    $controller = new Controllers\User();
    $controller->login();
});

$klein->respond('GET', '/login', function () {
    require_once APP_ROOT.'/views/user/login.php';
    exit;
});

$klein->respond('GET', '/logout', function () {
    $controller = new Controllers\User();
    $controller->logout();
});



$klein->onHttpError(function ($code, $router) {
    switch ($code) {
        case 404:
            $router->response()->body(
                '404'
            );
            break;
        default:
            $router->response()->body(
                'error '. $code
            );
    }
});