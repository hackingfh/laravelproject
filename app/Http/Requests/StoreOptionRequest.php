<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOptionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'values' => ['required', 'array', 'min:1'],
            'values.*.label' => ['required', 'string', 'max:255'],
            'values.*.value' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:select,radio,checkbox'],
            'required' => ['boolean'],
        ];
    }
}
