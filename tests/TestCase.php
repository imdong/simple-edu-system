<?php

namespace Tests;

use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Testing\TestResponse;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * @var string 登陆成功的教师 token
     */
    protected string $teacher_token = '';

    /**
     * @var string 登陆成功的学生 token
     */
    protected string $student_token;

    public function setUp(): void
    {
        parent::setUp();

        $this->teacher_token = Teacher::query()->find(2)->createToken('teacher')->accessToken;
        $this->student_token = Student::query()->find(2)->createToken('student')->accessToken;
    }

    /**
     * 发送已鉴权的请求
     *
     * @param string $method
     * @param string $uri
     * @param array $data
     * @return TestResponse
     */
    public function studentJson(string $method, string $uri, array $data = []): \Illuminate\Testing\TestResponse
    {
        return $this->json($method, sprintf('/api/student%s', $uri), $data, [
            'Authorization' => sprintf('Bearer %s', $this->student_token),
        ]);
    }

    /**
     * 发送已鉴权的请求
     *
     * @param string $method
     * @param string $uri
     * @param array $data
     * @return TestResponse
     */
    public function teacherJson(string $method, string $uri, array $data = []): \Illuminate\Testing\TestResponse
    {
        return $this->json($method, sprintf('/api/teacher%s', $uri), $data, [
            'Authorization' => sprintf('Bearer %s', $this->teacher_token),
        ]);
    }
}
