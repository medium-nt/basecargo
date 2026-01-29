<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TripRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type' => ['required', 'in:domestic,international'],
            'truck_number' => ['nullable', 'string', 'max:255'],
            'driver_name' => ['nullable', 'string', 'max:255'],
            'status' => ['nullable', 'in:planned,loading,in_transit,unloading,completed,cancelled'],
            'invoice_number' => ['nullable', 'string', 'max:255'],
            'loading_address' => ['nullable', 'string', 'max:1000'],
            'unloading_address' => ['nullable', 'string', 'max:1000'],
            'loading_date' => ['nullable', 'date'],
            'unloading_date' => ['nullable', 'date', 'after:loading_date'],
            'cost' => ['nullable', 'numeric', 'min:0'],
            'payment_status' => ['nullable', 'in:unpaid,partial,paid'],
            'details' => ['nullable', 'string', 'max:5000'],
        ];
    }

    public function messages(): array
    {
        return [
            'type.required' => 'Поле типа рейса обязательно для заполнения',
            'type.in' => 'Неверный тип рейса',

            'truck_number.max' => 'Номер фуры не должен превышать :max символов',
            'driver_name.max' => 'ФИО водителя не должно превышать :max символов',

            'status.in' => 'Неверный статус рейса',

            'invoice_number.max' => 'Номер инвойса не должен превышать :max символов',

            'loading_address.max' => 'Адрес погрузки не должен превышать :max символов',
            'unloading_address.max' => 'Адрес выгрузки не должен превышать :max символов',

            'loading_date.date' => 'Неверный формат даты загрузки',
            'unloading_date.date' => 'Неверный формат даты выгрузки',
            'unloading_date.after' => 'Дата выгрузки должна быть после даты загрузки',

            'cost.numeric' => 'Стоимость должна быть числом',
            'cost.min' => 'Стоимость не может быть отрицательной',

            'payment_status.in' => 'Неверный статус оплаты',

            'details.max' => 'Реквизиты не должны превышать :max символов',
        ];
    }
}
