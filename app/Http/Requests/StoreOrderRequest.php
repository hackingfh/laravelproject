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
            'shipping_address' => ['required', 'string'],
            'country_code' => ['required', 'string', 'max:5'],
            'phone_number' => ['required', 'string', 'min:6', 'max:15'],
            'payment_method' => ['required', 'string', 'in:card,paypal'],
            'notes' => ['nullable', 'string'],
            // Card details (optional if not 'card', but we can keep it flexible)
            'card_name' => ['nullable', 'string', 'max:255'],
            'card_number' => ['nullable', 'string', 'max:30'],
            'card_expiry' => ['nullable', 'string', 'max:10'],
            'card_cvv' => ['nullable', 'string', 'max:4'],
        ];
    }

    public function messages(): array
    {
        return [
            'country_code.required' => 'L\'indicatif pays est requis.',
            'country_code.max' => 'L\'indicatif pays ne peut pas dépasser 5 caractères.',
            'phone_number.required' => 'Le numéro de téléphone est requis.',
            'phone_number.min' => 'Le numéro de téléphone est trop court (min 6).',
            'phone_number.max' => 'Le numéro de téléphone est trop long (max 15).',
            'shipping_address.required' => 'L\'adresse de livraison est requise.',
            'payment_method.required' => 'La méthode de paiement est requise.',
            'payment_method.in' => 'Méthode de paiement invalide.',
            'card_name.max' => 'Le nom sur la carte est trop long.',
            'card_number.max' => 'Le numéro de carte est trop long (max 20).',
            'card_expiry.max' => 'La date d\'expiration est trop longue.',
            'card_cvv.max' => 'Le code CVV est trop long.',
        ];
    }
}
