<?php

namespace App\Router;

use Controller\AuthController;
use Controller\DatabaseinitController;
use Controller\FeedbackController;
use Controller\FileController;

class Router
{
    private $routes = [];
    public function __construct($routes)
    {
        $this->routes = $routes;
    }
    /**
     * Основной метод поиска и запуска экземпляра
     * класса с методом
     * @param mixed $uri
     */
    public function Run()
    {
        $uri = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
        $requestUri = explode("/", $uri);

        /**
         * разбираем входную строку
         * https://localhost/api/{MODULE_NAME}/{METHOD_NAME}
         * explode разобъет на массив из переменной  REQUEST_URI
         * https://localhost - $uri[0]
         * api               - $uri[1]
         * {CONTROLLER_NAME}     - $uri[2]
         * {METHOD_NAME}     - $uri[3]
         * $uri = explode("/", $uri);
         * перечисляем названия контроллеров  
         */
        
        // если moduleName и actionName пустые то
        if (empty($requestUri[2]) && empty($requestUri[3])) {
            header("Location: Site/Home/index.php");
            exit;
        }
        $moduleName = $requestUri[2];
        if (count($requestUri) > 3) {
            $actionName = $requestUri[3];
        }

        $this->checkSwagger($moduleName);
        $isFoundRoute = $this->checkRoutes($moduleName, $actionName);
        if (!$isFoundRoute) {
            exit;
        }
        $upperCaseControllerClass = ucfirst($moduleName);
        $controllerClass = "Controller\\" . $upperCaseControllerClass . "Controller";

        $controller = new $controllerClass();
        $strMethodName = $actionName . "Action";
        // вызов контроллера c методом
        $controller->{$strMethodName}();
    }

    /**
     * Проверка роутов
     * @param mixed $moduleName
     * @param mixed $actionName
     */
    private function checkRoutes($moduleName, $actionName)
    {
        // проверяем если запрос идет на сваггер
        if (!isset($moduleName) || !isset($actionName)) {
            header("HTTP/1.1 404 Not Found Route and Action");
            return false;
        }

        if (!array_key_exists($moduleName, $this->routes)) {
            header("HTTP/1.1 404 Not Found Route");
            return false;
        }
        foreach ($this->routes[$moduleName] as $key => $value) {
            if ($actionName == $value) {
                return true;
            }
        }
        header("HTTP/1.1 404 Not Found Route");
        return false;
    }


    /**
     * Проверка запроса на сваггер
     * @param mixed $moduleName
     */
    private function checkSwagger($moduleName)
    {
        if ($moduleName == SWAGGER) {
            header("Location: /Controller/documentation/index.php");
            exit;
        }
    }
}
