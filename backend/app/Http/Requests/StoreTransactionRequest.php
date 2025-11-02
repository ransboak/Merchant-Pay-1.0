<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTransactionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'merchant_id' => 'required|exists:merchants,id',
            'amount' => 'required|numeric|min:1',
            'payment_reference' => 'required|unique:transactions,payment_reference',
        ];
    }

    public function messages(): array
    {
        return [
            'merchant_id.exists' => 'Merchant not found.',
            'payment_reference.unique' => 'This payment reference already exists.',
        ];
    }
}

