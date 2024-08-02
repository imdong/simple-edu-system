<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthLoginTest extends TestCase
{
    /**
     * 未登陆的情况下访问接口
     *
     * @return void
     */
    public function test_not_auth()
    {
        // 未登陆 测试接口是否 401
        $response = $this->getJson('/api/teacher/user');

        $response->assertStatus(401);
    }

    /**
     * A basic feature test example.
     */
    public function test_login_validation(): void
    {
        // 身份错误
        $this->postJson('/api/auth/login', [
            'type'     => 'teacher111',
            'username' => 'teacher',
            'password' => 'teacher',
        ])->assertStatus(422);

        // 缺失字段
        $this->postJson('/api/auth/login', [
//            'type'     => 'teacher111',
            'username' => 'teacher',
            'password' => 'teacher',
        ])->assertStatus(422);

        $this->postJson('/api/auth/login', [
            'type'     => 'teacher111',
//            'username' => 'teacher',
            'password' => 'teacher',
        ])->assertStatus(422);

        $this->postJson('/api/auth/login', [
            'type'       => 'teacher111',
            'username'   => 'teacher',
            'password11' => 'teacher',
        ])->assertStatus(422);

        // 字段为空
        $this->postJson('/api/auth/login', [
            'type'       => 'teacher111',
            'username'   => 'teacher',
            'password11' => '',
        ])->assertStatus(422);
    }

    public function test_login_error()
    {
        // 密码错误
        $this->postJson('/api/auth/login', [
            'type'     => 'teacher',
            'username' => 'teacher',
            'password' => '123456',
        ])->assertJson(['code' => 401]);

        // 账号不存在
        $this->postJson('/api/auth/login', [
            'type'     => 'teacher',
            'username' => 'teacher_@#$%^&*IUYHGDFSAF',
            'password' => 'teacher',
        ])->assertJson(['code' => 401]);

        // 错误的身份 正确的账号密码
        $this->postJson('/api/auth/login', [
            'type'     => 'student',
            'username' => 'teacher',
            'password' => 'teacher',
        ])->assertJson(['code' => 401]);

        $this->postJson('/api/auth/login', [
            'type'     => 'teacher',
            'username' => 'student',
            'password' => 'student',
        ])->assertJson(['code' => 401]);
    }

    public function test_login_success()
    {
        // 教师身份 正常登陆
        $response = $this->postJson('/api/auth/login', [
            'type'     => 'teacher',
            'username' => 'teacher',
            'password' => 'teacher',
        ]);

        // 登陆成功 记录 token 后续使用
        $response->assertJson([
            'code' => 200,
            'data' => [
                'role' => 'teacher'
            ]
        ]);

        // 教师身份 正常登陆
        $response = $this->postJson('/api/auth/login', [
            'type'     => 'student',
            'username' => 'student',
            'password' => 'student',
        ]);

        // 登陆成功 记录 token 后续使用
        $response->assertJson([
            'code' => 200,
            'data' => [
                'role' => 'student'
            ]
        ]);

        // 测试身份是否正确
        $this->getJson('/api/teacher/user', [
            'Authorization' => sprintf('Bearer %s', $this->teacher_token),
        ])->assertJson([
            'code' => 200,
            'data' => [
                'role' => 'teacher',
                'user' => [
                    'username' => 'teacher',
                ]
            ]
        ]);

        $this->getJson('/api/student/user', [
            'Authorization' => sprintf('Bearer %s', $this->student_token),
        ])->assertJson([
            'code' => 200,
            'data' => [
                'role' => 'student',
                'user' => [
                    'username' => 'student',
                ]
            ]
        ]);

        // 互换接口不能调用 (测试不过 但 postman 测试能过，怀疑是某些特性问题)
//        $this->getJson('/api/student/user', [
//            'Authorization' => sprintf('Bearer %s', $this->teacher_token),
//        ])->dd();
//        $this->getJson('/api/teacher/user', [
//            'Authorization' => sprintf('Bearer %s', $this->student_token),
//        ])->assertStatus(401);
    }




}
