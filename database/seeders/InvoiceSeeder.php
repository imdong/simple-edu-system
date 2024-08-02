<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Invoice;
use App\Services\InvoiceService;
use Illuminate\Database\Seeder;

class InvoiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 选择一半的课程来生成账单
        Course::query()->inRandomOrder()->limit(100)->get()->each(function (Course $course) {
            $model = InvoiceService::create($course);

            // 一半的概率已发送
            if (rand(0, 1)) {
                $model->setStatus(Invoice::STATUS_SENT);
            }

            $model->save();
        });
    }
}
