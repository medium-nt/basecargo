<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CargoRateRequestRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'product_name' => ['nullable', 'string', 'max:500'],
            'material' => ['nullable', 'string', 'max:255'],
            'gross_weight_total' => ['nullable', 'numeric', 'min:0'],
            'volume_total' => ['nullable', 'numeric', 'min:0'],
            'net_weight_total' => ['nullable', 'numeric', 'min:0'],
            'delivery_address' => ['nullable', 'string', 'max:1000'],

            // Менеджерские поля
            'calculated_rate' => ['nullable', 'numeric', 'min:0.01'],
            'manager_notes' => ['nullable', 'string', 'max:2000'],

            // Файлы
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
            'files' => ['nullable', 'array', 'max:10'],
            'files.*' => ['file', 'mimes:xls,xlsx,csv', 'max:10240'],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $hasData = $this->hasFilledField();
            $hasFiles = $this->hasFile('files');

            if (! $hasData && ! $hasFiles) {
                $validator->errors()->add('files', 'Заполните хотя бы одно поле или загрузите Excel файл');
            }
        });
    }

    private function hasFilledField(): bool
    {
        $fields = ['product_name', 'material', 'gross_weight_total', 'volume_total', 'net_weight_total', 'delivery_address'];

        foreach ($fields as $field) {
            $value = $this->input($field);
            if (! empty($value) && $value !== '' && $value !== null) {
                return true;
            }
        }

        return false;
    }

    public function messages(): array
    {
        return [
            'product_name.max' => 'Наименование не должно превышать :max символов',
            'material.max' => 'Материал не должен превышать :max символов',
            'gross_weight_total.numeric' => 'Вес брутто должен быть числом',
            'gross_weight_total.min' => 'Вес брутто не может быть отрицательным',
            'volume_total.numeric' => 'Объём должен быть числом',
            'volume_total.min' => 'Объём не может быть отрицательным',
            'net_weight_total.numeric' => 'Вес нетто должен быть числом',
            'net_weight_total.min' => 'Вес нетто не может быть отрицательным',
            'delivery_address.max' => 'Адрес не должен превышать :max символов',

            'calculated_rate.numeric' => 'Ставка должна быть числом',
            'calculated_rate.min' => 'Ставка не может быть отрицательной',
            'manager_notes.max' => 'Заметки не должны превышать :max символов',

            'photo.image' => 'Файл должен быть изображением',
            'photo.mimes' => 'Фотография должна быть в формате: JPEG, PNG, JPG, WebP',
            'photo.max' => 'Размер фотографии не должен превышать 5MB',

            'files.max' => 'Максимум 10 файлов за одну загрузку',
            'files.*.file' => 'Загруженный файл не является валидным файлом',
            'files.*.mimes' => 'Файл должен быть в формате: XLS, XLSX, CSV',
            'files.*.max' => 'Размер файла не должен превышать 10MB',
        ];
    }
}
