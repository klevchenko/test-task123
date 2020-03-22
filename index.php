<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/vendor/autoload.php';

$klein = new \Klein\Klein();

define('ROOT', __DIR__);

require_once "routes.php";

$klein->dispatch();
