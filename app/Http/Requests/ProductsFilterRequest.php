<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductsFilterRequest extends FormRequest
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
        $rules = [
                 'price_from' => 'nullable|numeric|min:0',
                 'price_to' => 'nullable|numeric|min:0'
        ];
        return $rules;
            

        // [
        //     'code' => 'required|min:3|max:255|unique:products,code',
        //     'name' => 'required|min:3|max:255',
        //     'description' => 'required|min:5',
        //     'price' => 'required|numeric|min:1'
        // ];
    }
}
