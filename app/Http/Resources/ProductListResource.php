<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

class ProductListResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     * This method converts the product resource into an array containing:
     * - The product's id, title, image URL (if available), price,
     * - And a formatted updated_at timestamp.
     */
    /**
 * Transform the resource collection into an array.
 * This method converts the product resource into an array containing:
 * - The product's id, title, image URL (if available), price,
 * - And a formatted updated_at timestamp.
 */
public function toArray($request)
{
    return [
        'id' => $this->id,
        'title' => $this->title,
        'image_url' => $this->image ? Storage::url($this->image) : null,
        'price' => $this->price,
        'updated_at' => (new \DateTime($this->updated_at))->format('Y-m-d H:i:s'),
    ];
}
}
