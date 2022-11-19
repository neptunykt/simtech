<?php
namespace App\Models;

enum UserType {
    case UnAuthorized;
    case User;
    case Admin;
}