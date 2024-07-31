<?php

namespace App\Http\Requests;

use App\Models\Course;
use App\Models\Invoice;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreInvoiceRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'course_id' => [
                'required',
                Rule::exists(Course::class, 'id'),
                Rule::unique(Invoice::class, 'course_id')->whereNull('deleted_at'),
            ],
        ];
    }
}
