<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;
class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // Log what's being returned
        Log::info('ProductResource', [
            'product_id' => $this->id
        ]);
        
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'price' => $this->price,
            'image' => $this->image,
            'image_url' => $this->when(isset($this->image_url), $this->image_url),
            'published' => (bool) $this->published,
            'track_inventory' => (bool) $this->track_inventory,
            'quantity' => $this->quantity,
            'reorder_level' => $this->reorder_level,
            'category_id' => $this->category_id, // Add this
            'category' => $this->whenLoaded('category', function() {
                return [
                    'id' => $this->category->id,
                    'name' => $this->category->name,
                    'is_active' => (bool) $this->category->is_active
                ];
            }),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}