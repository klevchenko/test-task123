<?php
require_once __DIR__ . '/vendor/autoload.php';

$klein = new \Klein\Klein();

require_once "routes.php";

$klein->dispatch();
