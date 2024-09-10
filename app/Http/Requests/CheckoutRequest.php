<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => 'required|uuid|exists:users,id',
            'voucher_code' => 'nullable|string|exists:vouchers,code',
            'items' => 'required|array',
            'items.*.product_id' => 'required|uuid|exists:products,id',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.quantity' => 'required|integer|min:1'
        ];
    }

    public function attributes(): array
    {
        return [
            'user_id' => 'user ID',
            'voucher_code' => 'voucher code',
            'items' => 'items',
            'items.*.product_id' => 'product ID',
            'items.*.price' => 'price',
            'items.*.quantity' => 'quantity',
        ];
    }
}
