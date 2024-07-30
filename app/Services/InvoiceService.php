<?php

namespace App\Services;

use App\Exceptions\OperationDeniedException;
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
     * @param Course|Builder $course
     * @return Invoice
     */
    public static function create(Course|Builder $course): Invoice
    {
        $invoice = new Invoice();

        $invoice->fill([
            'course_id'  => $course->id,
            'student_id' => $course->student_id,
            'amount'     => $course->cost,
        ]);

        $invoice->setStatus(Invoice::STATUS_CREATED);

        return $invoice;
    }

    /**
     * 发送账单
     *
     * @param Request $request
     * @return $this
     * @throws OperationDeniedException
     */
    public function send(Request $request): self
    {
        // 检查状态
        if ($this->model->status != Invoice::STATUS_CREATED) {
            throw new OperationDeniedException('当前状态无法发送账单');
        }

        $this->model->setStatus(Invoice::PAY_STATUS_SENT);

        return $this;
    }
}
