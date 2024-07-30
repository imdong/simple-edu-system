<?php

namespace App\Models;

use App\Scopes\PaginationScope;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * 账单表
 *
 * @property int $id
 * @property int $course_id 关联课程 ID
 * @property int $student_id 关联学生 ID
 * @property int $amount 需要支付的金额
 * @property int $status 订单状态
 * @property string $pay_channel 付款渠道
 * @property string $pay_order_id 付款订单号
 * @property string $pay_amount 实际付款金额
 * @property Carbon $paid_at 付款时间
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 * @property Course $course 关联的课程
 * @property Student $student 关联的学生
 */
class Invoice extends Model
{
    use HasFactory, PaginationScope, softDeletes;

    // 定义一些订单状态
    const STATUS_CREATED = 1; // 订单已创建
    const STATUS_SENT = 2; // 已发送给学生
    const STATUS_PAYING = 3; // 学生付款中
    const STATUS_PAID = 4; // 学生已付款
    const STATUS_FINISHED = 5; // 已完成
    const STATUS_PAY_FAILED = 8; // 付款失败
    const STATUS_FAILED = 9; // 失败
    const STATUS_CLOSED = 0; // 关闭

    // 定义一些付款状态
    const PAY_STATUS_CREATED = 1; // 付款订单已创建
    const PAY_STATUS_SENT = 2; // 已付款

    protected $fillable = [
        'course_id',
        'student_id',
        'amount',
    ];

    /**
     * 自动转换时间
     *
     * @var string[] dates
     */
    protected array $dates = [
        'paid_at',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'paid_at'    => 'timestamp',
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp',
        'deleted_at' => 'timestamp',
    ];

    /**
     * 获取关联的课程
     *
     * @return Model
     */
    public function getCourseAttribute(): Model
    {
        return $this->hasOne(Course::class, 'id', 'course_id')->first();
    }

    /**
     * 获取关联的课程
     *
     * @return Model
     */
    public function getStudentAttribute(): Model
    {
        return $this->hasOne(Student::class, 'id', 'student_id')->first();
    }

    /**
     * 设置订单状态
     *
     * @param int $status
     * @return self
     */
    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

}
