<?php

namespace App\Services;

use App\Exceptions\OperationDeniedException;
use App\Exceptions\PayException;
use App\Models\Course;
use App\Models\Invoice;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class InvoiceService extends BaseService
{
    /**
     * @var Builder|Invoice|Model
     */
    protected Model|Invoice|Builder $model;

    /**
     * @var array|string[] 允许执行的操作
     */
    protected static array $actions = [
        'send',
        'pay',
    ];

    /**
     * @param Course|Builder $course
     * @return Invoice
     */
    public static function create(Course|Builder $course): Invoice
    {
        $invoice = new Invoice();

        $invoice->fill([
            'course_id'  => $course->id,
            'teacher_id' => $course->teacher_id,
            'student_id' => $course->student_id,
            'amount'     => bcmul($course->cost, 100),
        ]);

        $invoice->setStatus(Invoice::STATUS_CREATED);

        return $invoice;
    }

    /**
     * @return array|string[]
     */
    public static function getActions(): array
    {
        return self::$actions;
    }

    /**
     * 发送账单
     *
     * @return void
     * @throws OperationDeniedException
     */
    public function send(): void
    {
        // 检查状态
        if ($this->model->status != Invoice::STATUS_CREATED) {
            throw new OperationDeniedException('当前状态无法发送账单');
        }

        $this->model->setStatus(Invoice::PAY_STATUS_SENT);

        return;
    }

    /**
     * 支付账单
     *
     * @param Request $request
     * @throws \Throwable
     */
    public function pay(Request $request): void
    {
        $omise_token = $request->input('omise_token');

        // 只有支付失败与待付款才可以继续
        if (!in_array($this->model->status, [Invoice::STATUS_PAY_FAILED, Invoice::STATUS_SENT])) {
            throw new OperationDeniedException('当前账单状态不允许进行支付');
        }

        // 设置为付款中状态
        $this->model
            ->setStatus(Invoice::STATUS_PAYING)
            ->saveOrFail();

        try {
            $data = PayService::create($this->model->amount, sprintf(
                'buy %s Invoice(%d) %s',
                config('app.name'),
                $this->model->id,
                $this->model->course->course_name
            ))->omiseCharge($omise_token);

            // 保存付款信息
            $this->model->paid(
                'omise:charge',
                $data->getPaidId(),
                $data->getPaidAmount(),
                $data->getPaidAt(),
            )->save();

        } catch (PayException $e) {
            // 记录支付失败状态
            $this->model
                ->setStatus(Invoice::STATUS_PAY_FAILED)
                ->saveOrFail();

            throw $e;
        }
    }
}
