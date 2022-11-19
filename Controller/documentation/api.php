<?php
namespace Api;
use Controller\DatabaseinitControler;
define("PROJECT_ROOT_PATH", __DIR__ . "/../../");
require PROJECT_ROOT_PATH . ("/vendor/autoload.php");
$openapi = \OpenApi\Generator::scan([$_SERVER['DOCUMENT_ROOT'] . "/Controller/Api"]);
header('Content-Type: application/json');
echo $openapi->toJSON();