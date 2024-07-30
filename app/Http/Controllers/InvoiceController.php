<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInvoiceRequest;
use App\Http\Requests\UpdateInvoiceRequest;
use App\Models\Course;
use App\Models\Invoice;
use App\Services\InvoiceService;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): \Illuminate\Http\JsonResponse
    {
        return $this->successData(
            Invoice::query()->usePage()
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

        $invoice = InvoiceService::create($course);
        $invoice->saveOrFail();

        return $this->successData($invoice);
    }

    /**
     * Display the specified resource.
     */
    public function show(Invoice $invoice): \Illuminate\Http\JsonResponse
    {
        return $this->successData(
            $invoice->append(['course', 'student'])
        );
    }

    /**
     * Update the specified resource in storage.
     * @throws \Throwable
     */
    public function update(UpdateInvoiceRequest $request, Invoice $invoice): \Illuminate\Http\JsonResponse
    {
        $action = $request->input('action');
        $service = new InvoiceService($invoice);

        // 调用处理
        $service->$action($request);

        $invoice->saveOrFail();

        return $this->successData(
            $invoice
        );
    }

    /**
     * Remove the specified resource from storage.
     * @throws \Throwable
     */
    public function destroy(Invoice $invoice): \Illuminate\Http\JsonResponse
    {
        $invoice->deleteOrFail();

        return $this->success();
    }

}
