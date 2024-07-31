<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

/**
 * 学生表
 *
 * @property int $id
 * @property string $username
 * @property string $password
 * @property string $name
 * @property string $remember_token
 */
class Student extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, useUserRole;

    private string $role = 'student';

    protected $fillable = [
        'username',
        'password',
        'name',
        'remember_token',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'password' => 'hashed',
    ];

    /**
     * 通过给定的username获取用户实例。
     *
     * @param string $username
     * @return Student
     */
    public function findForPassport(string $username): Student
    {
        return $this->where('username', $username)->first();
    }

    /**
     * Set the current access token for the user.
     *
     * @param \Laravel\Passport\Token $accessToken
     * @return $this
     */
    public function withAccessToken($accessToken)
    {
        // 判断是否为自己
        if ($accessToken->name != $this->role) {
            return null;
        }

        $this->accessToken = $accessToken;

        return $this;
    }
}
