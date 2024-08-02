<?php

namespace Database\Seeders;

use App\Models\Course;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 先生成 2-2 的几个订单
        Course::factory()->count(20)->create([
            'teacher_id' => 2,
            'student_id' => 2
        ]);

        Course::factory()->count(500)->create();
    }
}
