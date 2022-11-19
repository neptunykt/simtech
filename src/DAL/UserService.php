<?php
namespace App\DAL;

use App\DAL\DatabaseService;

class UserService extends DatabaseService
{
    /**
     * Метод для поиска пользователя в БД по имени
     * @param string $userName
     */
    public function getUserByUserName($userName)
    {
        return $this->executeQuery("SELECT u.Id as Id, 
            u.UserName as UserName, u.Password as Password, 
            r.RoleName as RoleName FROM Users as u 
                JOIN UserRoles as ur ON u.Id = ur.UserId 
                JOIN Roles as r ON ur.RoleId = r.Id 
                    WHERE UserName = ?;", "s", array($userName));
    }

    
}