<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:products,slug'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock' => ['nullable', 'integer', 'min:0'],
            'reference' => ['nullable', 'string', 'max:32', 'unique:products,reference'],
            'is_visible' => ['boolean'],
            'collection_id' => ['nullable', 'exists:collections,id'],
            'images' => ['nullable', 'array'],
            'images.*' => ['string'],
            'material' => ['nullable', 'string'],
            'movement' => ['nullable', 'string'],
            'complications' => ['nullable', 'array'],
            'is_swiss_made' => ['boolean'],
            'warranty_years' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
