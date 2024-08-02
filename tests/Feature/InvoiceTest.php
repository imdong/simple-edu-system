<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Invoice;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class InvoiceTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_create(): void
    {
        // 获取一个课程
        $id = Course::query()->where('teacher_id', 2)->first()->id;

        // 删除对应的账单
        Invoice::query()->where('course_id', $id)->delete();

        // 创建账单 成功
        $this->teacherJson('POST', '/invoices', [
            'course_id'       => $id,
        ])->assertJson(['code' => 200]);

        // 再次创建失败
        $this->teacherJson('POST', '/invoices', [
            'course_id'       => $id,
        ])->assertStatus(422);
    }
}
