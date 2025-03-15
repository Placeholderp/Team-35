<?php

namespace App\Models\Api;

// Extend the base Product model from App\Models\Product
class Product extends \App\Models\Product
{
    /**
     * Override the default route key name for model binding.
     */
    public function getRouteKeyName()
    {
        // Return 'id' so that Laravel uses the product's id field when binding routes.
        return 'id';
    }
}
