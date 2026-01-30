<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class AttachToTripRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'cargo_ids' => ['required', 'array', 'min:1'],
            'cargo_ids.*' => ['exists:cargo_shipments,id'],
            'trip_id' => ['required', 'exists:trips,id'],
        ];
    }
}
