<?php

namespace App\Models;


/**
 * 学生表
 *
 * @property int $id
 * @property string $username
 * @property string $password
 * @property string $name
 * @property string $remember_token
 */
class Student extends User
{

    protected string $role = 'student';

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
}
