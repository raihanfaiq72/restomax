<?php

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

use App\Models\customer;
class CustomerStoreRequest extends FormRequest
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
        return [
            'name'           => 'required|string|max:255',
            'phone_number'   => 'required|string|max:20|unique:customers,phone_number',
            'email'          => 'required|email|max:255|unique:customers,email',
            'birth_date'     => 'required|date',
            'loyalty_tier'   => 'required|in:bronze,silver,gold',
            'loyality_points' => 'required|integer|min:0',
            'slug'           => 'required|string|unique:customers,slug',
        ];
    }
}
