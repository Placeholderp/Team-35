<?php

namespace App\Http\Resources;

use App\Enums\CustomerStatus;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

class CustomerResource extends JsonResource
{
    // Disable wrapping the resource in a "data" key.
    public static $wrap = false;

    /**
     * Transform the resource into an array.
     */
    public function toArray($request)
    {
        // Retrieve the shipping and billing addresses.
        $shipping = $this->shippingAddress;
        $billing = $this->billingAddress;

        $result = [
            // Return both id and user_id fields for API consistency
            'id' => $this->user_id,
            'user_id' => $this->user_id,
            'first_name' => $this->first_name,         
            'last_name' => $this->last_name,           
            'email' => $this->user->email ?? null,
            'phone' => $this->phone,
            // Convert integer status to boolean for frontend consistency                   
            'status' => (bool)$this->status, // Will convert 1 to true, 0 to false
            'created_at' => $this->created_at ? (new \DateTime($this->created_at))->format('Y-m-d H:i:s') : null,
            'updated_at' => $this->updated_at ? (new \DateTime($this->updated_at))->format('Y-m-d H:i:s') : null,
        ];

        // Safely handle shipping address
        if ($shipping) {
            $result['shippingAddress'] = [
                'id' => $shipping->id,
                'user_id' => $shipping->user_id,
                'address1' => $shipping->address1,
                'address2' => $shipping->address2,
                'city' => $shipping->city,
                'state' => $shipping->state,
                'zipcode' => $shipping->zipcode,
                'country_code' => $shipping->country->code ?? $shipping->country_code, 
            ];
        } else {
            $result['shippingAddress'] = [
                'address1' => '',
                'address2' => '',
                'city' => '',
                'state' => '',
                'zipcode' => '',
                'country_code' => '',
            ];
        }

        // Safely handle billing address
        if ($billing) {
            $result['billingAddress'] = [
                'id' => $billing->id,
                'user_id' => $billing->user_id,
                'address1' => $billing->address1,
                'address2' => $billing->address2,
                'city' => $billing->city,
                'state' => $billing->state,
                'zipcode' => $billing->zipcode,
                'country_code' => $billing->country->code ?? $billing->country_code,
            ];
        } else {
            $result['billingAddress'] = [
                'address1' => '',
                'address2' => '',
                'city' => '',
                'state' => '',
                'zipcode' => '',
                'country_code' => '',
            ];
        }

        return $result;
    }
}