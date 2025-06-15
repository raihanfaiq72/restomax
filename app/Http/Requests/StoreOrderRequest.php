<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // customer_id dan table_id bersifat opsional, tapi jika ada, harus valid
            'customer_id'       => 'nullable|exists:customers,id',
            'table_id'          => 'nullable|exists:tables,id',
            
            // 'items' wajib ada, harus berupa array, dan minimal berisi 1 item
            'items'             => 'required|array|min:1',
            
            // Validasi untuk setiap objek di dalam array 'items'
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity'  => 'required|integer|min:1',
        ];
    }
}
