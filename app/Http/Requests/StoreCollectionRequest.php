<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCollectionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', 'unique:collections,name'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:collections,slug'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'string'],
            'period_start' => ['nullable', 'integer', 'min:1900', 'max:2100'],
            'period_end' => ['nullable', 'integer', 'min:1900', 'max:2100'],
            'is_active' => ['boolean'],
        ];
    }
}
