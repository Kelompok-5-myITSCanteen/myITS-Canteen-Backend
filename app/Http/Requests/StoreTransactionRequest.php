<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTransactionRequest extends FormRequest
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
            'is_dine' => 'required|boolean',
            'time_in' => 'required_if:is_dine,true|date_format:H:i',
            'time_out' => 'required_if:is_dine,true|date_format:H:i|after:time_in',
            'total_price' => 'required|numeric|min:0',
            // 'is_discounted' => 'required|boolean',
            'discount' => 'required|numeric|min:0',
            'payment' => 'required|string|in:cash,card,qris',

            'cartItems' => 'required|array|min:1',
            'cartItems.*.id' => 'required|string|exists:menus,m_id',
            'cartItems.*.quantity' => 'required|integer|min:1',

            'kursi' => 'required_if:is_dine,true|array|min:1',
            'kursi.*' => 'required|string|exists:chairs,ch_id',
        ];
    }
}
