<?php

namespace App\Models;

trait useUserRole
{
    /**
     * @return string 用于扩充用户 Role 身份获取
     */
    public function getUserRole(): string
    {
        return $this->role;
    }
}
