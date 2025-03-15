<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Nette\Utils\DateTime;

class UserResource extends JsonResource
{
    public static $wrap = false;

    /**
     * Transform the resource into an array.
     * 
     * This method converts the user model's data into an array format,
     * including the id, name, email, and a formatted creation timestamp.
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'created_at' => (new DateTime($this->created_at))->format('Y-m-d H:i:s'),
        ];
    }
}
