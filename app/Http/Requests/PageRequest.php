<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PageRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'limit' => ['int'],
            'page'  => ['int'],
        ];
    }

    /**
     * 获取当前页码
     *
     * @param int $default
     * @return int
     */
    public function page(int $default = 1): int
    {
        return $this->query('page', $default);
    }

    /**
     * 获取单页条数
     *
     * @param int $default
     * @return int
     */
    public function limit(int $default = 10): int
    {
        return $this->query('limit', $default);
    }
}
