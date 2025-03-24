<?php

namespace App\Http\Resources\Dashboard;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public static $wrap = false;

    /**
     * Transform the resource into an array.
     *
     *  This method formats the order data for dashboard consumption, including:
     * - Basic order information (ID, total price).
     * - The order items.
     * - Associated user and customer details.
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'total_price' => $this->total_price,
            'created_at' => $this->created_at->diffForHumans(),
            'items' => $this->items,
            'user_id' => $this->user_id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
        ];
    }
}
