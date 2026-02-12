<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExportSelectedRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'cargo_ids' => ['required', 'array', 'min:1'],
            'cargo_ids.*' => ['exists:cargo_shipments,id'],
        ];
    }
}
