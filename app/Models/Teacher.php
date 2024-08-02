<?php

namespace App\Models;

use App\Models\Scopes\TeacherScope;

/**
 * 教师表
 *
 * @property string $username
 * @property string $password
 * @property string $name
 * @property string $type
 * @property string $remember_token
 */
class Teacher extends User
{

    protected $table = 'admin_users';

    protected string $role = User::USER_ROLE_TEACHER;

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
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp',
    ];

    /**
     * 自动转换时间
     *
     * @var string[] dates
     */
    protected $dates = [
        'created_at',
        'updated_at',
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

}
