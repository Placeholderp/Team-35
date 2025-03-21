<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Http\Requests\ProductRequest;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Clean a product ID by removing anything after a colon
     * 
     * @param string|int $id
     * @return string|int
     */
    private function cleanProductId($id)
    {
        if (is_string($id) && strpos($id, ':') !== false) {
            return explode(':', $id)[0];
        }
        return $id;
    }
    
    /**
     * Display a listing of the resource.
     */
    /**
 * Display a listing of the resource.
 */
public function index()
{
    $search = request('search', false);
    $perPage = request('per_page', 10);
    $sortField = request('sort_field', 'updated_at');
    $sortDirection = request('sort_direction', 'desc');
    $includeCategory = request('include', ''); 

    $query = Product::query();

    if (strpos($includeCategory, 'category') !== false) {
        $query->with('category');
    }
    
    // Apply search filter
    if ($search) {
        $query->where('title', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%");
    }

    // Apply category filter if provided
    if (request()->has('category_id') && request('category_id') !== '') {
        $query->where('category_id', request('category_id'));
    }

    // Apply stock status filter
    if (request()->has('stock_status') && request('stock_status') !== '') {
        $stockStatus = request('stock_status');
        
        if ($stockStatus === 'low_stock') {
            $query->where('track_inventory', true)
                  ->where('quantity', '>', 0)
                  ->whereRaw('quantity <= reorder_level');
        } else if ($stockStatus === 'out_of_stock') {
            $query->where('track_inventory', true)
                  ->where('quantity', 0);
        } else if ($stockStatus === 'in_stock') {
            $query->where('track_inventory', true)
                  ->where('quantity', '>', 0)
                  ->whereRaw('quantity > reorder_level');
        }
    }
    
    // Debug the SQL query
    Log::info('Product query SQL', ['sql' => $query->toSql(), 'bindings' => $query->getBindings()]);

    // Apply sorting
    $query->orderBy($sortField, $sortDirection);

    // Get paginated results
    $products = $query->paginate($perPage);

    /** @var \Illuminate\Pagination\LengthAwarePaginator $products */
    $products->getCollection()->transform(function ($product) {
        // Ensure published is always set
        if (!isset($product->published)) {
            $product->published = false;
        }

        // Add image URL if image exists
        if ($product->image) {
            $product->image_url = $this->getImageUrl($product->image);
        }

        return $product;
    });

    return ProductResource::collection($products);
}

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductRequest $request)
    {
        $data = $request->validated();
        $data['published'] = $request->boolean('published', false);

        // Generate a unique slug
        $data['slug'] = Str::slug($data['title']) . '-' . Str::random(5);

        // Handle image upload
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
            $data['image_mime'] = $request->file('image')->getMimeType();
            $data['image_size'] = $request->file('image')->getSize();
            
            // Debug image path
            Log::info('Image upload path', [
                'image_path' => $data['image'],
                'full_url' => URL::to(Storage::url($data['image']))
            ]);
        }

        // Set created_by and updated_by
        $data['created_by'] = $request->user()->id;
        $data['updated_by'] = $request->user()->id;

        // Create the product
        $product = Product::create($data);

        // Add image URL if present
        if ($product->image) {
            $product->image_url = $this->getImageUrl($product->image);
        }

        return new ProductResource($product);
    }

    /**
     * Display the specified resource.
     * 
     * @param string|int $id
     * @return \App\Http\Resources\ProductResource
     */
    public function show($id)
    {
        // Clean the ID
        $cleanId = $this->cleanProductId($id);
        
        // Find the product with the cleaned ID
        $product = Product::findOrFail($cleanId);
        
        // Ensure published is always set
        if (!isset($product->published)) {
            $product->published = false;
        }

        // Add image URL if present
        if ($product->image) {
            $product->image_url = $this->getImageUrl($product->image);
            
            // Debug image path
            Log::info('Image URL for product', [
                'product_id' => $product->id,
                'image_path' => $product->image,
                'image_url' => $product->image_url
            ]);
        }

        return new ProductResource($product);
    }

    /**
     * Update the specified resource in storage.
     * 
     * @param \App\Http\Requests\ProductRequest $request
     * @param string|int $id
     * @return \App\Http\Resources\ProductResource
     */
    /**
 * Update the specified resource in storage.
 * 
 * @param \App\Http\Requests\ProductRequest $request
 * @param string|int $id
 * @return \App\Http\Resources\ProductResource
 */
public function update(ProductRequest $request, $id)
{
    // Clean the ID
    $cleanId = $this->cleanProductId($id);
    
    // Find the product with the cleaned ID
    $product = Product::findOrFail($cleanId);
    
    $data = $request->validated();
    $data['updated_by'] = $request->user()->id;
    
    // Handle published field conversion
    $data['published'] = $request->boolean('published', false);
    
    // Handle track_inventory field conversion
    if ($request->has('track_inventory')) {
        $data['track_inventory'] = $request->boolean('track_inventory', false);
    }
    
    // Handle category_id - treat empty string as null
    if ($request->has('category_id')) {
        $data['category_id'] = $request->input('category_id') !== '' 
            ? $request->input('category_id') 
            : null;
        
        // Add debug log for category
        Log::info('Category data in update', [
            'product_id' => $product->id,
            'category_id' => $data['category_id']
        ]);
    }
    
    Log::info('Updating product', [
        'product_id'     => $product->id,
        'published'      => $data['published'],
        'published_type' => gettype($data['published']),
        'has_image'      => isset($data['image']) ? 'yes' : 'no',
        'category_id'    => $data['category_id'] ?? null
    ]);
    
    // Handle image upload
    if ($request->hasFile('image')) {
        // Log before saving
        Log::info('Image before save', [
            'file_name' => $request->file('image')->getClientOriginalName(),
            'file_size' => $request->file('image')->getSize()
        ]);
        
        // Delete old image if it exists
        if ($product->image) {
            Log::info('Deleting old image', ['old_path' => $product->image]);
            Storage::disk('public')->delete($product->image);
        }
        
        // Save the new image
        $relativePath = $request->file('image')->store('products', 'public');
        
        // Save path to data array
        $data['image'] = $relativePath;
        $data['image_mime'] = $request->file('image')->getMimeType();
        $data['image_size'] = $request->file('image')->getSize();
        
        // Log after saving
        Log::info('Image after save', [
            'relative_path' => $relativePath,
            'full_url' => URL::to(Storage::url($relativePath))
        ]);
    } else if ($request->boolean('_remove_image')) {
        // Handle image removal
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        $data['image'] = null;
        $data['image_mime'] = null;
        $data['image_size'] = null;
    }
    
    // Remove _remove_image from data before update
    if (isset($data['_remove_image'])) {
        unset($data['_remove_image']);
    }
    
    $product->update($data);
    
    // IMPORTANT: Load the product with the category relationship
    $product = Product::with('category')->find($product->id);
    
    // Add image_url to the response if image exists
    if ($product->image) {
        $product->image_url = $this->getImageUrl($product->image);
        
        // Debug the final image URL
        Log::info('Final image URL', [
            'image_path' => $product->image,
            'image_url' => $product->image_url
        ]);
    }
    
    // Log the full product before returning
    Log::info('Updated product before return', [
        'id' => $product->id,
        'has_category' => $product->category ? 'yes' : 'no',
        'category_id' => $product->category_id,
        'category_name' => $product->category ? $product->category->name : null
    ]);
    
    return new ProductResource($product);
}
    /**
 * Function to generate correct image URL
 * 
 * @param string $path
 * @return string
 */
/**
 * Function to generate correct image URL
 * 
 * @param string $path
 * @return string|null
 */
/**
 * Function to generate correct image URL
 * 
 * @param string $path
 * @return string|null
 */
private function getImageUrl($path)
{
    if (!$path) {
        return null;
    }
    
    // Use Storage facade to generate proper URL regardless of environment
    $url = Storage::url($path);
    
    // Log the generated URL for debugging
    Log::info('Generated image URL', [
        'path' => $path,
        'url' => $url
    ]);
    
    return $url;
}


    /**
     * Remove the specified resource from storage.
     * 
     * @param string|int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Clean the ID
        $cleanId = $this->cleanProductId($id);
        
        // Find the product with the cleaned ID
        $product = Product::findOrFail($cleanId);
        
        // Delete image if it exists
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return response()->noContent();
    }
}