<?php

namespace App\Http\Requests;

use App\Enums\CustomerStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class CustomerRequest extends FormRequest
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
   // In app/Http/Requests/CustomerRequest.php
public function rules()
{
    $rules = [
        'first_name' => ['required'],
        'last_name' => ['required'],
        'email' => ['required', 'email'],
        'status' => ['nullable'], // Changed from 'required|boolean' to 'nullable'
    ];
    
    // Only apply address validation when creating a new customer
    // or when specifically updating addresses
    if ($this->isMethod('post') || $this->has('shippingAddress')) {
        $rules['shippingAddress.address1'] = ['nullable'];
        $rules['shippingAddress.address2'] = ['nullable'];
        $rules['shippingAddress.city'] = ['nullable'];
        $rules['shippingAddress.state'] = ['nullable'];
        $rules['shippingAddress.zipcode'] = ['nullable'];
        $rules['shippingAddress.country_code'] = ['nullable', 'exists:countries,code'];
    }
    
    if ($this->isMethod('post') || $this->has('billingAddress')) {
        $rules['billingAddress.address1'] = ['nullable'];
        $rules['billingAddress.address2'] = ['nullable'];
        $rules['billingAddress.city'] = ['nullable'];
        $rules['billingAddress.state'] = ['nullable'];
        $rules['billingAddress.zipcode'] = ['nullable'];
        $rules['billingAddress.country_code'] = ['nullable', 'exists:countries,code'];
    }
    
    return $rules;
}

    public function attributes()
    {
        return [
            'billingAddress.address1' => 'address 1',
            'billingAddress.address2' => 'address 2',
            'billingAddress.city' => 'city',
            'billingAddress.state' => 'state',
            'billingAddress.zipcode' => 'zip code',
            'billingAddress.country_code' => 'country',
            'shippingAddress.address1' => 'address 1',
            'shippingAddress.address2' => 'address 2',
            'shippingAddress.city' => 'city',
            'shippingAddress.state' => 'state',
            'shippingAddress.zipcode' => 'zip code',
            'shippingAddress.country_code' => 'country',
        ];
    }
}
