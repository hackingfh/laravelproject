<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => ['required', 'exists:users,id'],
            'total' => ['required', 'numeric', 'min:0'],
            'status' => ['required', 'in:pending,paid,shipped,delivered,cancelled'],
            'shipping_address' => ['required', 'array'],
            'payment_method' => ['required', 'string', 'max:64'],
            'payment_status' => ['nullable', 'string', 'max:32'],
            'tracking_number' => ['nullable', 'string', 'max:64'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
