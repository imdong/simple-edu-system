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

            $table->unsignedBigInteger('course_id')->comment('关联课程 ID')->unique();
            $table->unsignedBigInteger('student_id')->comment('关联学生 ID');
            $table->decimal('amount', 8, 4)->comment('需要支付的金额');
            $table->smallInteger('status')->default(0)->comment('订单状态');

            // $table->smallInteger('pay_status')->default(0)->comment('付款状态');
            $table->string('pay_channel')->nullable()->comment('付款渠道');
            $table->string('pay_order_id')->nullable()->comment('付款订单号');
            $table->string('pay_amount', 8, 4)->nullable()->comment('实际付款金额');

            $table->timestamp('paid_at')->nullable()->comment('付款时间');

            $table->timestamps();
            $table->softDeletes();
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
