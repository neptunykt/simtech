<?php
namespace App\Services;

use App\Models\UserType;

class HelperService {

/**
 * Метод для проверки требуемой роли пользователя
 */
public static function checkUserRole($roles, UserType $userType) {
    foreach ($roles as $key => $value) {
            if($value == strtolower($userType->name)) {
                return true;
            }     
    }
    return false;
}

}