<?php
namespace App\DAL;

require PROJECT_ROOT_PATH . "inc/config.php";

use \mysqli;
use \Exception;
use App\Models\DbRequestType;

// require PROJECT_ROOT_PATH . "Models/DbRequestType.php";
class DatabaseService
{
    protected $connection = null;

    public function __construct()
    {
        try {
            // используем mysqli
            $this->connection = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_DATABASE_NAME);

            if (mysqli_connect_errno()) {
                throw new Exception("Невозможно подключиться к базе.");
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * Метод для получения результата запроса
     * @param mixed $query
     * @param mixed $types
     * @param array $params
     * @param enum $dbRequestType
     * @return mixed $result
     */
    public function executeQuery($query, $types, $params = [], DbRequestType $dbRequestType = DbRequestType::Select)
    {
       
        try {
            switch ($dbRequestType) {
                case DbRequestType::Select:
                    $stmt = $this->executeStatement($query, $types, $params);
                    $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
                    break;
                case DbRequestType::InsertWithReturnId:
                    $this->executeStatement($query, $types, $params);
                    // возврат вставленной айдишки
                    $result = $this->connection->insert_id;
                    break;
                case DbRequestType::Execute:
                    $this->executeStatement($query, $types, $params);
                    $result = true;
                    break;
            } 
            if($dbRequestType == DbRequestType::Select){
                $stmt->close();
            }
            return $result;
        } catch (Exception $e) {
            echo $query . "<br>";
            throw new Exception($e->getMessage());
        }
        return false;
    }


    /**
     * @param mixed $query строка запроса
     * @param mixed $types типы параметров
     * @param array $params массив параметров
     * @return mixed $smtp
     */
    private function executeStatement($query = "", $types, $params = [])
    {
        try {
            $stmt = $this->connection->prepare($query);

            if ($stmt === false) {
                throw new Exception("Невозможно подготовить выражение: " . $query);
            }
            // ...$params разворачивает массив в параметры
            // $types символьная строка с параметрами
            // i => integer
            // d => double
            // s => string
            // b => blob      
            if ($params) {
                $stmt->bind_param($types, ...$params);
            }

            $stmt->execute();
            return $stmt;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
}
