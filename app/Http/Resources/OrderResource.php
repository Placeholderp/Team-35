<?php
// File: app/Http/Resources/OrderResource.php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        try {
            // Make sure user exists and has a customer profile
            if (!$this->user || !$this->user->customer) {
                return $this->getBasicOrderData();
            }

            $customer = $this->user->customer;

            // Check if addresses exist
            $hasShipping = $customer->shippingAddress !== null;
            $hasBilling = $customer->billingAddress !== null;

            return [
                'id' => $this->user_id, // Frontend expects 'id'
                'user_id' => $this->user_id, // Include original field too for compatibility
                'status' => $this->status,
                'total_price' => $this->total_price,
                // Map order items to include product details.
                'items' => $this->mapOrderItems(),
                // Customer details including basic info and addresses.
                'customer' => [
                    'id' => $this->user->id,
                    'email' => $this->user->email,
                    'first_name' => $customer->first_name ?? '',
                    'last_name' => $customer->last_name ?? '',
                    'phone' => $customer->phone ?? '',
                    // Shipping address details with null safety.
                    'shippingAddress' => $hasShipping ? $this->formatAddress($customer->shippingAddress) : $this->getEmptyAddress(),
                    // Billing address details with null safety.
                    'billingAddress' => $hasBilling ? $this->formatAddress($customer->billingAddress) : $this->getEmptyAddress(),
                ],
                'created_at' => $this->created_at ? $this->created_at->format('Y-m-d H:i:s') : now()->format('Y-m-d H:i:s'),
                'updated_at' => $this->updated_at ? $this->updated_at->format('Y-m-d H:i:s') : now()->format('Y-m-d H:i:s'),
            ];
        } catch (\Exception $e) {
            Log::error('Error formatting order resource: ' . $e->getMessage());
            return $this->getBasicOrderData();
        }
    }

    /**
     * Map order items with error handling
     */
    private function mapOrderItems()
    {
        try {
            // Make sure items is iterable
            if (!$this->items || !$this->items->count()) {
                return [];
            }
            
            return $this->items->map(function($item) {
                try {
                    return [
                        'id' => $item->user_id,
                        'unit_price' => $item->unit_price,
                        'quantity' => $item->quantity,
                        'product' => [
                            'id' => $item->product->id ?? 0,
                            'slug' => $item->product->slug ?? '',
                            'title' => $item->product->title ?? 'Product not available',
                            'image' => $item->product->image ?? '',
                        ]
                    ];
                } catch (\Exception $e) {
                    Log::error('Error mapping order item: ' . $e->getMessage());
                    return [
                        'id' => $item->user_id ?? 0,
                        'unit_price' => $item->unit_price ?? 0,
                        'quantity' => $item->quantity ?? 0,
                        'product' => [
                            'id' => 0,
                            'slug' => '',
                            'title' => 'Product data error',
                            'image' => '',
                        ]
                    ];
                }
            });
        } catch (\Exception $e) {
            Log::error('Error processing order items: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Format address data with error handling
     */
    private function formatAddress($address)
    {
        try {
            return [
                'id' => $address->id,
                'address1' => $address->address1,
                'address2' => $address->address2 ?? '',
                'city' => $address->city,
                'state' => $address->state ?? '',
                'zipcode' => $address->zipcode,
                'country' => $address->country->name ?? 'Unknown',
            ];
        } catch (\Exception $e) {
            Log::error('Error formatting address: ' . $e->getMessage());
            return $this->getEmptyAddress();
        }
    }

    /**
     * Get empty address data
     */
    private function getEmptyAddress()
    {
        return [
            'id' => 0,
            'address1' => '',
            'address2' => '',
            'city' => '',
            'state' => '',
            'zipcode' => '',
            'country' => '',
        ];
    }

    /**
     * Get basic order data when relationships fail
     */
    private function getBasicOrderData()
    {
        return [
            'id' => $this->user_id,
            'user_id' => $this->user_id,
            'status' => $this->status,
            'total_price' => $this->total_price,
            'items' => [],
            'customer' => [
                'id' => $this->created_by ?? 0,
                'email' => $this->user->email ?? '',
                'first_name' => '',
                'last_name' => '',
                'shippingAddress' => $this->getEmptyAddress(),
                'billingAddress' => $this->getEmptyAddress(),
            ],
            'created_at' => $this->created_at ? $this->created_at->format('Y-m-d H:i:s') : now()->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at ? $this->updated_at->format('Y-m-d H:i:s') : now()->format('Y-m-d H:i:s'),
        ];
    }
}