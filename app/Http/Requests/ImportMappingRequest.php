<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ImportMappingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'mapping' => ['required', 'array'],
            'mapping.*' => ['required', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'mapping.required' => 'Необходимо указать соответствие колонок',
            'mapping.array' => 'Маппинг должен быть массивом',
            'mapping.*.required' => 'Все колонки должны быть замаплены',
            'mapping.*.string' => 'Неверный формат маппинга',
        ];
    }
}
