<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UsersRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = $this->route('user');
        $isUpdating = $userId !== null;

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($userId)],
            'phone' => ['nullable', 'string', 'min:7', 'max:100'],
            'messenger' => ['nullable', 'in:telegram,whatsapp,wechat,viber'],
            'messenger_number' => ['nullable', 'string', 'max:100'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ];

        // role_id только при создании
        if (!$isUpdating) {
            $rules['role_id'] = ['required', 'integer', Rule::in([1, 2, 4])];
        } else {
            $rules['role_id'] = ['prohibited'];
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'role_id.required' => 'Поле роли обязательно для заполнения',
            'role_id.integer' => 'Роль должна быть числом',
            'role_id.exists' => 'Указанная роль не существует',

            'name.required' => 'Поле имени обязательно для заполнения',
            'name.string' => 'Имя должно быть строкой',
            'name.max' => 'Имя не должно превышать :max символов',

            'email.required' => 'Поле email обязательно для заполнения',
            'email.string' => 'Email должен быть строкой',
            'email.email' => 'Неверный формат email',
            'email.max' => 'Email не должен превышать :max символов',
            'email.unique' => 'Такой email уже существует',

            'phone.string' => 'Телефон должен быть строкой',
            'phone.min' => 'Телефон должен содержать минимум :min символов',
            'phone.max' => 'Телефон не должен превышать :max символов',

            'messenger.in' => 'Неверный тип мессенджера',

            'messenger_number.string' => 'Номер в мессенджере должен быть строкой',
            'messenger_number.max' => 'Номер в мессенджере не должен превышать :max символов',

            'password.string' => 'Пароль должен быть строкой',
            'password.min' => 'Пароль должен содержать минимум :min символов',
            'password.confirmed' => 'Подтверждение пароля не совпадает',
        ];
    }
}
