<?php

// get and show all posts
$klein->respond('GET', '/', function () {
    require_once ROOT."/controllers/PostController.php";
    $controller = new PostController();
    $controller->index();
    exit;
});

// show new post form
$klein->respond('GET', '/post/create', function () {
    require_once ROOT.'/views/posts/create.php';
    exit;
});

// new post form
$klein->respond('POST', '/post/create', function () {
    require_once ROOT."/controllers/PostController.php";
    $controller = new PostController();
    $controller->store();
});

// show edit post form
$klein->respond('get', '/post/edit/[:id]', function ($request) {
    require_once ROOT."/controllers/PostController.php";
    $controller = new PostController();
    $controller->getOne($request->id);
});

// update post
$klein->respond('POST', '/post/update', function () {
    require_once ROOT."/controllers/PostController.php";
    $controller = new PostController();
    $controller->update();
});

// login/logout routes
$klein->respond('POST', '/login', function () {
    require_once ROOT."/controllers/UserController.php";
    $controller = new UserController();
    $controller->login();
});

$klein->respond('GET', '/login', function () {
    require_once ROOT.'/views/user/login.php';
    exit;
});

$klein->respond('GET', '/logout', function () {
    require_once ROOT."/controllers/UserController.php";
    $controller = new UserController();
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