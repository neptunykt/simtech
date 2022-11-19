<?php
namespace App\Services;

use Ahc\Jwt\JWT;
use \DateTimeImmutable;

class TokenService {
    
    private $secretKey  = "bGS6lzFqvvSQ8ALbOxatm7/Vk7mLQyzqaS34Q4oR1ew=";
    private $serverName = "http://api.simtech.ru";

    /**
     * Метод для создания токена
     * @param mixed $user
     * @return array jwt-token 
     */
    public function encode($user) {
        $roles = [];
        // тут придет массив user-ов с разными ролями
        foreach ($user as $object) {
            $roles[] = $object["RoleName"];
            }
        $payload = array(
            "iss" => $this->serverName,
            // даем токен на сутки
            "exp" => time() + 60*60*24, 
            "uid" => $user[0]["Id"],
            "roles" => $roles
            );
            $jwtHandler = new JWT($this->secretKey, "HS256", 3600, 10);
            $jwt = $jwtHandler->encode($payload);
             $this->decode($jwt);
            // var_dump($jwtHandler->decode($jwt));
            $result["status"] = true;
            $result["token"] = $jwt;
            $result["userName"] = $user[0]["UserName"];
            return $result;    
    }

    /**
     * Метод для проверки токена
     * @param mixed $jwt
     * @return boolean $result
     */
    public function decode($jwt) {
        $jwtHandler = new JWT($this->secretKey, "HS256", 3600, 10);
        $token = $jwtHandler->decode($jwt);
        $now = new DateTimeImmutable();

    if ($token["iss"] !== $this->serverName ||
        $token["exp"] < $now->getTimestamp()) {
        return [];
        } else {
            return $token["roles"];
        }
    }
}