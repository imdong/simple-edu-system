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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('course_id')->comment('关联课程 ID');
            $table->unsignedBigInteger('teacher_id')->comment('关联课程的老师ID');
            $table->unsignedBigInteger('student_id')->comment('关联学生 ID');
            $table->bigInteger('amount')->comment('需要支付的金额(最小货币单位)');
            $table->smallInteger('status')->default(0)->comment('订单状态');

            // $table->smallInteger('pay_status')->default(0)->comment('付款状态');
            $table->string('pay_channel')->nullable()->comment('付款渠道');
            $table->string('pay_order_id')->nullable()->comment('付款订单号');
            $table->string('pay_amount')->nullable()->comment('实际付款金额');

            $table->timestamp('paid_at')->nullable()->comment('付款时间');

            $table->timestamps();
            $table->softDeletes();

            $table->unique(['course_id', 'deleted_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
