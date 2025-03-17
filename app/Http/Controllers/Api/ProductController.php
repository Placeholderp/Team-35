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
    public function index()
    {
        $search = request('search', false);
        $perPage = request('per_page', 10);
        $sortField = request('sort_field', 'updated_at');
        $sortDirection = request('sort_direction', 'desc');

        $query = Product::query();

        // Apply search filter
        if ($search) {
            $query->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
        }

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
                $product->image_url = URL::to(Storage::url($product->image));
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
            $product->image_url = URL::to(Storage::url($product->image));
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
            $product->image_url = URL::to(Storage::url($product->image));
            
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
    
        Log::info('Updating product', [
            'product_id'     => $product->id,
            'published'      => $data['published'],
            'published_type' => gettype($data['published']),
            'has_image'      => isset($data['image']) ? 'yes' : 'no'
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
        $product->refresh();
    
        // Add image_url to the response if image exists
        if ($product->image) {
            $product->image_url = URL::to(Storage::url($product->image));
            
            // Debug the final image URL
            Log::info('Final image URL', [
                'image_path' => $product->image,
                'image_url' => $product->image_url
            ]);
        }
    
        return new ProductResource($product);
    }
    /**
 * Function to generate correct image URL
 * 
 * @param string $path
 * @return string
 */
private function getImageUrl($path)
{
    // First try using asset helper
    $url = asset('storage/' . $path);
    
    // If we're on the Vite dev server (localhost:5173), ensure URL matches
    if (request()->getHost() === 'localhost:5173' || request()->getHost() === '127.0.0.1:5173') {
        // Replace http://localhost with http://localhost:5173 if needed
        if (strpos($url, 'http://localhost/') === 0 && strpos($url, 'http://localhost:5173/') !== 0) {
            $url = str_replace('http://localhost/', 'http://localhost:5173/', $url);
        }
    }
    
    Log::info('Generated image URL', [
        'path' => $path,
        'generated_url' => $url
    ]);
    
    return $url;
}

// Then replace all instances of:
// $product->image_url = URL::to(Storage::url($product->image));

// With:
// $product->image_url = $this->getImageUrl($product->image);

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