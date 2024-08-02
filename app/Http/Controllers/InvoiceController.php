<?php

namespace App\Http\Controllers;

use App\Exceptions\OperationDeniedException;
use App\Http\Requests\StoreInvoiceRequest;
use App\Http\Requests\UpdateInvoiceRequest;
use App\Models\Course;
use App\Models\Invoice;
use App\Models\User;
use App\Services\InvoiceService;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     * @throws OperationDeniedException
     */
    public function index(Request $request): \Illuminate\Http\JsonResponse
    {
        /**
         * @var  User $user
         */
        $user = $request->user();
        if ($user->cannot('viewAny', Invoice::class)) {
            throw new OperationDeniedException('无权限执行该操作');
        }

        $query = Invoice::query();

        // 如果是学生 额外加个条件
        if ($user->getUserRole() == 'student') {
            $query->where('status', '!=', Invoice::STATUS_CREATED);
        }

        return $this->successData(
            $query->with(['student', 'teacher', 'course'])->usePage()
        );
    }

    /**
     * Store a newly created resource in storage.
     * @throws \Throwable
     */
    public function store(StoreInvoiceRequest $request): \Illuminate\Http\JsonResponse
    {
        $course_id = $request->input('course_id');
        $course = Course::query()
            ->findOrFail($course_id);

        /**
         * @var  User $user
         */
        $user = $request->user();
        if ($user->cannot('create', $course)) {
            throw new OperationDeniedException('无权限执行该操作');
        }

        // 创建数据独
        $invoice = InvoiceService::create($course);
        $invoice->saveOrFail();

        return $this->successData($invoice);
    }

    /**
     * Display the specified resource.
     * @throws OperationDeniedException
     */
    public function show(Request $request, Invoice $invoice): \Illuminate\Http\JsonResponse
    {
        /**
         * @var  User $user
         */
        $user = $request->user();
        if ($user->cannot('view', $invoice)) {
            throw new OperationDeniedException('无权限执行该操作');
        }

        return $this->successData(
            $invoice->append(['course', 'student', 'teacher'])
        );
    }

    /**
     * Update the specified resource in storage.
     * @throws \Throwable
     */
    public function update(UpdateInvoiceRequest $request, Invoice $invoice): \Illuminate\Http\JsonResponse
    {
        $action = $request->input('action');

        /**
         * @var  User $user
         */
        $user = $request->user();
        if ($user->cannot('update', [$invoice, $action])) {
            throw new OperationDeniedException('无权限执行该操作');
        }

        $service = new InvoiceService($invoice);

        // 调用处理
        $service->$action($request);

        $invoice->saveOrFail();

        return $this->successData($invoice);
    }

    /**
     * Remove the specified resource from storage.
     * @throws \Throwable
     */
    public function destroy(Request $request, Invoice $invoice): \Illuminate\Http\JsonResponse
    {
        /**
         * @var  User $user
         */
        $user = $request->user();
        if ($user->cannot('delete', [$invoice])) {
            throw new OperationDeniedException('无权限执行该操作');
        }

        $invoice->deleteOrFail();

        return $this->success();
    }

}
