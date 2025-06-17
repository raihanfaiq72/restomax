<?php

namespace App\Http\Requests\Ingredients;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class IngredientsUpdateRequest extends FormRequest
{
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

    public function rules(): array
    {
        $ingredient = $this->route('ingredient');

        return [
            'name'  => [
                'required',
                'string',
                'max:255',
                Rule::unique('ingredients')->ignore($ingredient->id),
            ],
            'stock_quantity' => 'required|numeric|min:0',
            'unit'  => 'required|string|max:50',
            'low_stock_threshold' => 'required|numeric|min:0',
            'slug' => [
                'required',
                'string',
                Rule::unique('ingredients')->ignore($ingredient->id),
            ],
        ];
    }
}