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
    public function store(Request $request)
    {
        $data = $request->validate([
            'title'       => 'required|string|max:2000',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'nullable|string',
            'price'       => 'required|numeric',
            'published'   => 'boolean',
        ]);

        $data['published'] = $request->boolean('published', false);

        // Generate a unique slug
        $data['slug'] = Str::slug($data['title']) . '-' . Str::random(5);

        // Handle image upload
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
            $data['image_mime'] = $request->file('image')->getMimeType();
            $data['image_size'] = $request->file('image')->getSize();
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
     * * @return \App\Http\Resources\ProductResource
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
        }

        return new ProductResource($product);
    }

    /**
     * Update the specified resource in storage.
     * 
     * @param \App\Http\Requests\ProductRequest $request
     * @param string|int $id
     * * @return \App\Http\Resources\ProductResource
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
        if (isset($data['published'])) {
            if (
                $data['published'] === '1' ||
                $data['published'] === 1 ||
                $data['published'] === 'true' ||
                $data['published'] === true
            ) {
                $data['published'] = true;
            } else {
                $data['published'] = false;
            }
        } else {
            $data['published'] = false;
        }

        Log::info('Updating product', [
            'product_id'     => $product->id,
            'published'      => $data['published'],
            'published_type' => gettype($data['published']),
        ]);

        $image = $data['image'] ?? null;
        if ($image) {
            // Save the image using the saveImage() method
            $relativePath = $this->saveImage($image);
            $data['image'] = URL::to(Storage::url($relativePath));
            $data['image_mime'] = $image->getClientMimeType();
            $data['image_size'] = $image->getSize();

            // Delete old image if it exists
            if ($product->image) {
                Storage::deleteDirectory('/public/' . dirname($product->image));
            }
        }

        $product->update($data);
        $product->refresh();

        return new ProductResource($product);
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

    /**
     * Save uploaded image and return the relative path.
     *
     * @param \Illuminate\Http\UploadedFile $image
     * @return string
     */
    private function saveImage($image)
    {
        return $image->store('products', 'public');
    }
}