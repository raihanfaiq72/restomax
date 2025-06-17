<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

use App\Models\product;

class ProductStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if ($this->name) {
            $this->merge([
                'slug' => Str::slug($this->name),
            ]);
        }
    }


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'  => 'required',
            'sku'   => 'required',
            'price' => 'required|numeric|min:0',
            'description'=>'nullable|string',
            'category_id'=> 'required|exists:categories,id',
            'slug' => 'required|string|unique:products,slug',
            'is_available' => 'required|boolean',
        ];
    }

}
