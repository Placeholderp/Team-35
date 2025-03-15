<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

class CountryResource extends JsonResource
{
    public static $wrap = false;

    /**
     * Transform the resource into an array.
     *  Returns the country code, name, and a decoded array of states.
     */
    public function toArray($request)
    {
        return [
            'code' => $this->code,
            'name' => $this->name,
            'states' => json_decode($this->states, true),
        ];
    }
}
