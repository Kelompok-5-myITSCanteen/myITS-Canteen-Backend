<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Carbon\Carbon;

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
            'time_in' => 'required_if:is_dine,true|date_format:Y-m-d H:i:s',
            'time_out' => 'required_if:is_dine,true|date_format:Y-m-d H:i:s|after:time_in',
            'total_price' => 'required|numeric|min:0',
            // 'is_discounted' => 'required|boolean',
            'discount' => 'required|numeric|min:0',
            'payment' => 'required|string|in:cash,card,qris',

            'k_id' => 'required|string|exists:canteens,k_id',

            'cartItems' => 'required|array|min:1',
            'cartItems.*.id' => 'required|string|exists:menus,m_id',
            'cartItems.*.quantity' => 'required|integer|min:1',

            'kursi' => 'required_if:is_dine,true|array|min:1',
            'kursi.*' => 'required|string|exists:chairs,ch_id',
        ];

    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->is_dine) {
                $this->validateOperatingHours($validator);
            }
        });
    }

    public function validateOperatingHours($validator){
        try{
            $timeIn = Carbon::parse($this->time_in);
            $timeOut = Carbon::parse($this->time_out);
            
            $opening_time = $timeIn->copy()->setTime(8, 0, 0); 
            if ($timeIn->lt($opening_time)){
                $validator->errors()->add('time_in', 'Waktu masuk tidak boleh sebelum jam buka (08:00).');
            }

            $closing_time = $timeOut->copy()->setTime(16, 0, 0); 
            if ($timeOut->gt($closing_time)){
                $validator->errors()->add('time_out', 'Waktu keluar tidak boleh setelah jam tutup (16:00).');
            }


        } catch (\Exception $e) {
            $validator->errors()->add('time_in', 'Format waktu tidak valid atau di luar jam operasional.');
        }
    }
}