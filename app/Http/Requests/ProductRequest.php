<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules()
    {
        return [
            'title' => ['required', 'max:2000'],
            'image' => ['nullable', 'image'],
            'price' => ['required', 'numeric'],
            'description' => ['nullable', 'string'],
            'published' => ['required', 'boolean'],
            'category_id' => ['nullable', 'exists:categories,id'], // Add this line
            'track_inventory' => ['nullable', 'boolean'],          // Add these too if needed
            'quantity' => ['nullable', 'numeric', 'min:0'],
            'reorder_level' => ['nullable', 'numeric', 'min:0']
        ];
    }
}