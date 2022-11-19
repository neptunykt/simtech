<?php
namespace App\Models;

enum DbRequestType {
    case Select;
    case InsertWithReturnId;
    case Execute;
}