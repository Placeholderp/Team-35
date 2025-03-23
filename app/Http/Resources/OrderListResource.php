<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\log;

class OrderListResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     * This method formats an order's data into an array for API responses.
     */
    public function toArray($request)
    {
        try {
            $userData = $this->user ? [
                'id' => $this->user->id,
                // Use user's name instead of 'Unknown' when customer doesn't exist
                'first_name' => $this->user->customer ? $this->user->customer->first_name : $this->user->name,
                'last_name' => $this->user->customer ? $this->user->customer->last_name : '',
                'email' => $this->user->email ?? '',
            ] : [
                'id' => 0,
                'first_name' => 'Unknown',
                'last_name' => 'Customer',
                'email' => '',
            ];

            return [
                'id' => $this->user_id, 
                'status' => $this->status,
                'total_price' => $this->total_price,
                'number_of_items' => $this->whenLoaded('items', function() {
                    return $this->items->count();
                }, 0),
                'customer' => $userData,
                'created_at' => (new \DateTime($this->created_at))->format('Y-m-d H:i:s'),
                'updated_at' => (new \DateTime($this->updated_at))->format('Y-m-d H:i:s'),
            ];
        } catch (\Exception $e) {
            // Your exception handling...
            return [
                'id' => $this->user_id,
                'status' => $this->status,
                'total_price' => $this->total_price,
                'customer' => [
                    'id' => $this->created_by ?? 0,
                    'first_name' => $this->user ? $this->user->name : 'Error',
                    'last_name' => '',
                    'email' => $this->user ? $this->user->email : '',
                ],
                'created_at' => $this->created_at ? (new \DateTime($this->created_at))->format('Y-m-d H:i:s') : now()->format('Y-m-d H:i:s'),
                'updated_at' => $this->updated_at ? (new \DateTime($this->updated_at))->format('Y-m-d H:i:s') : now()->format('Y-m-d H:i:s'),
            ];
        }
    }
}