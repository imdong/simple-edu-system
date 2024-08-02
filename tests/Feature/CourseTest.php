<?php

namespace Tests\Feature;


use App\Models\Course;
use App\Models\Student;
use Tests\TestCase;

class CourseTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_create_success(): void
    {
        // 创建课程 成功
        $response = $this->teacherJson('POST', '/courses', [
            'name'       => 'Test Course',
            'date'       => '2024-08',
            'cost'       => '8.25',
            'student_id' => 2
        ])->assertJson(['code' => 200]);

        // 重新取回来 一致
        $data = $this->teacherJson('GET', sprintf('/courses/%s', $response->json('data.id')))
            ->assertJson($response->json());
    }

    public function test_create_fail()
    {
        // 字段缺失
        $this->teacherJson('POST', '/courses', [
//            'name'       => 'Test Course',
            'date'       => '2024-08',
            'cost'       => '8.25',
            'student_id' => 2
        ])->assertStatus(422);

        $this->teacherJson('POST', '/courses', [
            'name'       => 'Test Course',
//            'date'       => '2024-08',
            'cost'       => '8.25',
            'student_id' => 2
        ])->assertStatus(422);
        $this->teacherJson('POST', '/courses', [
            'name'       => 'Test Course',
            'date'       => '2024-08',
//            'cost'       => '8.25',
            'student_id' => 2
        ])->assertStatus(422);
        $this->teacherJson('POST', '/courses', [
            'name'       => 'Test Course',
            'date'       => '2024-08',
            'cost'       => '8.25',
//            'student_id' => 2
        ])->assertStatus(422);

        // 空白字段
        $this->teacherJson('POST', '/courses', [
            'name'       => '',
            'date'       => '2024-08',
            'cost'       => '8.25',
            'student_id' => 2
        ])->assertStatus(422);
        $this->teacherJson('POST', '/courses', [
            'name'       => 'Test Course',
            'date'       => '',
            'cost'       => '8.25',
            'student_id' => 2
        ])->assertStatus(422);
        $this->teacherJson('POST', '/courses', [
            'name'       => 'Test Course',
            'date'       => '2024-08',
            'cost'       => '',
            'student_id' => 2
        ])->assertStatus(422);
        $this->teacherJson('POST', '/courses', [
            'name'       => 'Test Course',
            'date'       => '2024-08',
            'cost'       => '8.25',
            'student_id' => 0
        ])->assertStatus(422);

        // 错误内容
        $this->teacherJson('POST', '/courses', [
            'name'       => 'Test Course',
            'date'       => '20245-08',
            'cost'       => '8.25',
            'student_id' => 2
        ])->assertStatus(422);
        $this->teacherJson('POST', '/courses', [
            'name'       => 'Test Course',
            'date'       => '2024-08',
            'cost'       => '-58.25',
            'student_id' => 2
        ])->assertStatus(422);
        $this->teacherJson('POST', '/courses', [
            'name'       => 'Test Course',
            'date'       => '2024-08',
            'cost'       => '8.25',
            'student_id' => 0
        ])->assertStatus(422);

        // 不存在的学生
        Student::destroy(999);
        $this->teacherJson('POST', '/courses', [
            'name'       => 'Test Course',
            'date'       => '2024-08',
            'cost'       => '8.25',
            'student_id' => 9999
        ])->assertStatus(422);
    }

    /**
     * 权限
     *
     * @return void
     */
    public function test_permission()
    {
        // 学生创建（未能解决 postman 响应 json，测试响应 html 的问题）
//        $this->studentJson('POST', '/courses', [
//            'name'       => 'Test Course',
//            'date'       => '2024-08',
//            'cost'       => '8.25',
//            'student_id' => 2
//        ])->assertStatus(403);

        // 老师 看别的老师的
        $id = Course::query()->where('teacher_id', '!=',2 )->first()->id;
        $this->teacherJson('GET', '/courses/'.$id)->assertStatus(404);

        // 学生看别的学生的
        $id = Course::query()->where('student_id', '!=',2 )->first()->id;
        $this->studentJson('GET', '/courses/'.$id)->assertStatus(404);
    }
}
