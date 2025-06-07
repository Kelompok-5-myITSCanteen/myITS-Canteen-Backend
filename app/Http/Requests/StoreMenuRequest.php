<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMenuRequest extends FormRequest
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
            'm_name' => 'required|string|max:60',
            'm_price' => 'required|numeric|min:0|max:1000000',
            'm_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'm_category' => 'required|string|in:makanan,minuman,snack',
            'm_stock' => 'required|integer|min:1|max:1000',
        ];
    }
}
