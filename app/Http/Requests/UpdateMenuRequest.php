<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateMenuRequest extends FormRequest
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
            'm_name' => 'sometimes|string|max:60|unique:menus,m_name,' . $this->route('menu')->m_id . ',m_id',
            'm_price' => 'sometimes|numeric|min:0|max:1000000',
            'm_image' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'm_category' => 'sometimes|string|in:Makanan,Minuman,Snack',
            'm_stock' => 'sometimes|integer|min:1|max:10000',
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
