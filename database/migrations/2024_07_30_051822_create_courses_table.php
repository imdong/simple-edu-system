<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();

            $table->string('name', 64)->comment('课程名');
            $table->date('date')->comment('课程年月');
            $table->decimal('cost')->comment('费用');
            $table->unsignedBigInteger('teacher_id')->comment('创建课程的老师ID');
            $table->unsignedBigInteger('student_id')->comment('学生');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
