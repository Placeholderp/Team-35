<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

class CustomerListResource extends JsonResource
{
    public static $wrap = false;

    /**
     * Transform the resource into an array.
     * This method converts the customer resource into an array that includes:
     * - The customer's ID (using the user_id field)
     * - First name and last name
     * - Email, retrieved from the associated user record
     * - Phone number, status, and formatted creation/update timestamps
     */
    public function toArray($request)
    {
        return [
            'id' => $this->user_id,
            'user_id' => $this->user_id, // Include both id and user_id for consistency
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->user->email ?? null,
            'status' => $this->status,
            'created_at' => $this->created_at ? (new \DateTime($this->created_at))->format('Y-m-d H:i:s') : null,
            'updated_at' => $this->updated_at ? (new \DateTime($this->updated_at))->format('Y-m-d H:i:s') : null,
        ];
    }
}
