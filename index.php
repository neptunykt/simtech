<?php

namespace App;
define("PROJECT_ROOT_PATH", __DIR__ . "/");


use App\Router\Router;

require_once PROJECT_ROOT_PATH . "inc/routes.php";
include PROJECT_ROOT_PATH . "vendor/autoload.php";

$router = new Router(ROUTES);
$router->Run();
