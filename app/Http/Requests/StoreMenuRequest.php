<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreMenuRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'm_name'     => 'required|string|max:60|unique:menus,m_name',
            'm_price'    => 'required|numeric|min:0|max:1000000',
            'm_image'    => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'm_category' => 'required|string|in:Makanan,Minuman,Snack',
            'm_stock'    => 'required|integer|min:1|max:10000',
        ];
    }

    protected function prepareForValidation()
    {
        $data = [];
        // Capitalize first letter in each word
        if ($this->has('m_name')) {
            $data['m_name'] = ucwords(strtolower($this->m_name));
        }
        if ($this->has('m_category')) {
            $data['m_category'] = ucfirst(strtolower($this->m_category));
        }
        $this->merge($data);
    }
}
