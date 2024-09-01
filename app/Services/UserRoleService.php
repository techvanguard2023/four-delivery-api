<?php

namespace App\Services;

class UserRoleService
{
    public static function getUserRoleId($user)
    {
        return $user->roles()->first()->pivot->role_id; // Obtém o role_id da tabela pivot
    }
}
