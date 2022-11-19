<?php

namespace Controller;

class BaseController
{
    /**
     * NOT_FOUND метод
     */
    public function __call($name, $arguments)
    {

        $this->sendOutput("", array("HTTP/1.1 404 Not Found"));
    }

    /**
     * Получение URI элементов
     * @return array
     */
    protected function getUriSegments()
    {
        $uri = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
        $uri = explode("/", $uri);

        return $uri;
    }

    /**
     * Получение GET запроса
     * @return array
     */
    protected function getQueryStringParams()
    {
        // прохождение символа ? настроен в nginx для GET
        $queryArray = explode("?", $_SERVER["QUERY_STRING"]);
        if (count($queryArray) > 1) {
            parse_str($queryArray[1], $query);
            return $query;
        }
    }

    /**
     * Основной метод для отправки
     * с заполнением заголовка
     * @param mixed $data
     * @param array $httpHeaders
     */
    function sendOutput($data, $httpHeaders = array())
    {
        header_remove("Set-Cookie");

        if (is_array($httpHeaders) && count($httpHeaders)) {
            foreach ($httpHeaders as $httpHeader) {
                header($httpHeader);
            }
        }
        echo ($data);
        exit;
    }

    protected function sendOk()
    {
        header("HTTP/1.1 204 OK");
        exit;
    }

    /**
     * Метод для отправки положительного результата
     * в виде json
     * @param mixed $data
     */
    protected function sendResult($data)
    {
        $this->sendOutput(
            json_encode($data),
            array("Content-Type: application/json")
        );
    }


    /**
     * Метод для отправки ошибок с описанием ошибки
     * @param mixed $errorModel
     */
    protected function sendError($errorModel)
    {
        $this->sendOutput(
            json_encode(array("error" => $errorModel->description)),
            array("Content-Type: application/json", $errorModel->header)
        );
    }
}
