<?php
namespace Controller;

use PhpParser\Node\Name;
use App\DAL\UserService;
// require PROJECT_ROOT_PATH . "DAL/UserService.php";
use App\Services\TokenService;
// require PROJECT_ROOT_PATH . "Services/TokenService.php";
use Controller\BaseController;
// require PROJECT_ROOT_PATH . "Controller/Api/BaseController.php";
use App\Models\ErrorResponseModel;

class AuthController extends BaseController
{

    public function loginAction() {

        $requestMethod = $_SERVER["REQUEST_METHOD"];
        $arrQueryStringParams = $this->getQueryStringParams();
        $userName = NULL;
        $password = NULL;
        // Для безопасности не рекомендуется, но можно авторизоваться через GET для примера
        if (strtoupper($requestMethod) == "GET") {

            if (isset($arrQueryStringParams["userName"]) && $arrQueryStringParams["userName"]) {
                $userName = $arrQueryStringParams["userName"];
            }
            if (isset($arrQueryStringParams["password"]) && $arrQueryStringParams["password"]) {
                $password = $arrQueryStringParams["password"];
            }
            // создается коннект на базу
            $userService = new UserService();
            if (!$this->checkUserCredentials($userName, $password, $userService, $user)) {
                exit;
            }
            // Все ок - даем токен пользователю
            $tokenService = new TokenService();
            $token = $tokenService->encode($user);
            $this->sendOutput($token);
        } else if (strtoupper($requestMethod) == "POST") {
            $userName = htmlspecialchars($_POST["userName"]);
            $password = htmlspecialchars($_POST["password"]);
            // создается коннект на базу
            $userService = new UserService();
            if (!$this->checkUserCredentials($userName, $password, $userService, $user)) {
                exit;
            }
            // Все ок - даем токен пользователю
            $tokenService = new TokenService();
            $token = $tokenService->encode($user);
            $this->sendResult($token);
        }
    }


    /** 
     * Метод для проверки существования
     * пользователя с паролем
     * @param mixed $userName
     * @param mixed $password
     * @param mixed $userServise
     * @param mixed $user
     */
    private function checkUserCredentials($userName, $password, $userService, &$user) {

        $user = $userService->getUserByUserName($userName);
        if (empty($user)) {
            $errorResponse = new ErrorResponseModel();
            $errorResponse->description = "Логин или пароль неправильный";
            $errorResponse->header = "HTTP/1.1 404 Not found";
            $this->sendError($errorResponse);
            return false;
        }
        // проверяем пароль

        // echo "<br>" . "verify=" . password_verify($password,$user[0]["Password"]);
        $logical = password_verify($password, $user[0]["Password"]) == 1;
        // echo "<br>" . "verify logical=" . $logical . "<br>";
        if ($logical != 1) {
            $errorResponse = new ErrorResponseModel();
            $errorResponse->description = "Логин или пароль неправильный";
            $errorResponse->header = "HTTP/1.1 404 Not found";
            $this->sendError($errorResponse);
            return false;
        }
        return true;
    }

    /**
     * Метод для выхода из авторизации
     */
    public function logoutAction() {
        $requestMethod = $_SERVER["REQUEST_METHOD"];
        if (strtoupper($requestMethod) == "POST") {
        session_start();
        unset($_SESSION["token"]);
        }

    }
}
