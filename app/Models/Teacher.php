<?php

namespace App\Models;

use App\Models\Scopes\TeacherScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

/**
 * 教师表
 *
 * @property string $username
 * @property string $password
 * @property string $name
 * @property string $type
 * @property string $remember_token
 */
class Teacher extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, useUserRole;

    protected $table = 'admin_users';

    private string $role = 'teacher';

    protected $fillable = [
        'username',
        'password',
        'name',
        'remember_token',
        'avatar',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'type',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'password' => 'hashed',
    ];

    protected static function boot()
    {
        parent::boot();

        // 只允许查询 type = teacher
        static::addGlobalScope(new TeacherScope());
    }

    /**
     * 通过给定的username获取用户实例。
     *
     * @param string $username
     * @return \App\Models\Teacher
     */
    public function findForPassport(string $username): Teacher
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
