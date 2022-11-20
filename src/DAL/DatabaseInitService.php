<?php
namespace App\DAL;

use App\DAL\DatabaseService;
use App\Models\DbRequestType;
use \Exception;

class DatabaseInitService extends DatabaseService
{
    /**
     * Метод для создания таблиц в бд
     * Нужны гранты для создания таблиц в базе
     */
    public function createTables()
    {
        try {
            $query = "DROP DATABASE IF EXISTS `simtech`;";
            $this->executeQuery($query, "", [], DbRequestType::Execute);
            $query = "CREATE DATABASE `simtech` CHARACTER SET 'utf8' COLLATE 'utf8_general_ci';";
            $this->executeQuery($query, "", [], DbRequestType::Execute);
            $query = "CREATE TABLE `simtech`.`Users` (
            `Id` int NOT NULL AUTO_INCREMENT,
            `UserName` varchar(255) NOT NULL,
            `Password` varchar(255) NOT NULL,
            PRIMARY KEY (`Id`));";
            $this->executeQuery($query, "", [], DbRequestType::Execute);
            $query = "CREATE TABLE `simtech`.`Roles` (
            `Id` INT NOT NULL AUTO_INCREMENT,
            `RoleName` varchar(255) NOT NULL,
            PRIMARY KEY (`Id`));";
            $this->executeQuery($query, "", [], DbRequestType::Execute);
            $query = "CREATE TABLE `simtech`.`Feedbacks` (
            `Id` INT NOT NULL AUTO_INCREMENT,
            `CreatedOn` DATETIME,
            `Email` varchar(255),
            `IsAgreed` BOOLEAN,
            `Sex` BOOLEAN,
            `Country` INT,
            `Message` varchar(255),
            `FileName` varchar(255),
            PRIMARY KEY (`Id`)
        );";
            $this->executeQuery($query, "", [], DbRequestType::Execute);
            $query = "CREATE TABLE `simtech`.`UserRoles` (
            `Id` INT NOT NULL AUTO_INCREMENT,
            `UserId` INT NOT NULL,
            `RoleId` INT NOT NULL,
            FOREIGN KEY (`UserId`)
                 REFERENCES `Users`(`Id`),
            FOREIGN KEY (`RoleId`)
             REFERENCES `Roles`(`Id`),
            PRIMARY KEY (`Id`));";
            $this->executeQuery($query, "", [], DbRequestType::Execute);
            $hashPassword = "\$2y\$10\$NNOXnNQoCN9n6s/0dPboS.iiFFMQRztea6cQo0KcbOgsX6CeNVdyu";
            $query = "INSERT INTO `simtech`.`Users` (`UserName`,`Password`) VALUES 
                     ('admin','" . $hashPassword . "');";
            $this->executeQuery($query, "", [], DbRequestType::Execute);
            $query = "INSERT INTO `simtech`.`Roles` (`RoleName`) VALUES ('admin')";
            $this->executeQuery($query, "", [], DbRequestType::Execute);
            $query = "INSERT INTO `simtech`.`Roles` (`RoleName`) VALUES ('user');";
            $this->executeQuery($query, "", [], DbRequestType::Execute);
            $query = "INSERT INTO `simtech`.UserRoles (`RoleId`,`UserId`) VALUES (1,1);";
            $this->executeQuery($query, "", [], DbRequestType::Execute);
            $query = "INSERT INTO `simtech`.UserRoles (`RoleId`,`UserId`) VALUES (2,1);";
            $this->executeQuery($query, "", [], DbRequestType::Execute);
        } catch (Exception $ex) {
            throw ($ex);
        }
    }
}
