<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\Token;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * 用户模型的身份常量标识
     */
    const USER_ROLE_USER = 'user';
    const USER_ROLE_TEACHER = 'teacher';
    const USER_ROLE_STUDENT = 'student';

    protected string $role = self::USER_ROLE_USER;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
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
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
    ];

    /**
     * Set the current access token for the user.
     *
     * @param Token $accessToken
     * @return User|null
     */
    public function withAccessToken($accessToken): null|static
    {
        // 判断是否为自己
        if ($accessToken->name != $this->getUserRole()) {
            return null;
        }

        $this->accessToken = $accessToken;

        return $this;
    }

    /**
     * @return string 用于扩充用户 Role 身份获取
     */
    public function getUserRole(): string
    {
        return $this->role;
    }
}
