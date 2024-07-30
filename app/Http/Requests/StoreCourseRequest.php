<?php

namespace App\Http\Requests;

use App\Models\Student;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCourseRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'       => ['required', 'string'],
            'date'       => ['required', 'date_format:Y-m'],
            'cost'       => ['required', 'numeric', 'min:0'],
            'student_id' => ['required', 'integer', Rule::exists(Student::class, 'id')],
        ];
    }
}
