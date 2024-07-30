<?php

namespace App\Http\Requests;

use App\Models\Student;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCourseRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'       => ['filled', 'string'],
            'date'       => ['filled', 'date_format:Y-m'],
            'cost'       => ['filled', 'numeric', 'min:0'],
            'student_id' => ['filled', 'integer', Rule::exists(Student::class, 'id')],
        ];
    }
}
