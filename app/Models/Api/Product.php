<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Product extends Model
{
    use HasFactory;
    use HasSlug;
    use SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'price',
        'published',
        'image',
        'image_mime',
        'image_size',
        'track_inventory',
        'quantity',
        'reorder_level',
        'category_id',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'published' => 'boolean',
        'track_inventory' => 'boolean',
        'quantity' => 'integer',
        'reorder_level' => 'integer',
        'price' => 'float',
    ];

    protected static function booted()
{
    // Assign a default category when creating a product if none is specified
    static::creating(function ($product) {
        if (empty($product->category_id)) {
            // Get the first category or create a default one if none exists
            $defaultCategory = Category::first();
            
            if (!$defaultCategory) {
                $defaultCategory = Category::create([
                    'name' => 'General Products',
                    'slug' => 'general-products',
                    'description' => 'Default category for all products'
                ]);
            }
            
            $product->category_id = $defaultCategory->id;
        }
    });
}

    /**
     * Get the category that owns the product.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }

    /**
     * Get the inventory movements for this product.
     */
    public function inventoryMovements()
    {
        return $this->hasMany(InventoryMovement::class);
    }

    /**
     * Check if the product is low on stock.
     */
    public function isLowStock()
    {
        return $this->track_inventory && $this->quantity <= $this->reorder_level;
    }

    /**
     * Check if the product is out of stock.
     */
    public function isOutOfStock()
    {
        return $this->track_inventory && $this->quantity <= 0;
    }

    /**
     * Check if the product has sufficient stock for the requested quantity.
     *
     * @param int $requestedQuantity
     * @return bool
     */
    public function hasAvailableStock($requestedQuantity = 1)
    {
        return !$this->track_inventory || $this->quantity >= $requestedQuantity;
    }

    /**
     * Set default inventory values for a new product.
     *
     * @return $this
     */
    public function setDefaultInventoryValues()
    {
        $this->track_inventory = false;
        $this->quantity = 0;
        $this->reorder_level = 5;
        return $this;
    }

    /**
     * Adjust inventory and record the movement.
     *
     * @param int $quantity Positive to increase, negative to decrease
     * @param string $type Type of movement (purchase, sale, adjustment, etc)
     * @param string|null $reference Reference number or information
     * @param string|null $notes Additional notes
     * @return $this
     */
    public function adjustInventory($quantity, $type, $reference = null, $notes = null)
    {
        $previousQuantity = $this->quantity;
        $newQuantity = $previousQuantity + $quantity;
        
        // Create inventory movement record
        $this->inventoryMovements()->create([
            'quantity' => $quantity,
            'previous_quantity' => $previousQuantity,
            'new_quantity' => $newQuantity,
            'type' => $type,
            'reference' => $reference,
            'notes' => $notes,
            'created_by' => auth()->id() ?? 1 // Default to system user if no auth
        ]);
        
        // Update product quantity
        $this->quantity = $newQuantity;
        $this->save();
        
        return $this;
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}