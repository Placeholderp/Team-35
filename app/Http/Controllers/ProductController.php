<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Resources\ProductResource;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate the request data
        $validated = $request->validate([
            'title' => 'required|string|max:2000',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'published' => 'boolean',
            'track_inventory' => 'boolean',
            'quantity' => 'nullable|numeric|min:0',
            'reorder_level' => 'nullable|numeric|min:0',
            'category_id' => 'nullable|exists:categories,id',
        ]);
        
        // Set boolean fields explicitly to avoid null values
        $validated['published'] = $request->boolean('published', false);
        $validated['track_inventory'] = $request->boolean('track_inventory', false);
        
        // Set user who created the product
        if (auth()->check()) {
            $validated['created_by'] = auth()->id();
            $validated['updated_by'] = auth()->id();
        }
        
        // Create the product
        $product = new Product($validated);
        $product->save();
        
        return new ProductResource($product);
    }

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
     * Display a listing of the resource for API.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Product::query();
        
        // Apply search filter
        if ($request->filled('search')) {
            $searchTerm = '%' . $request->search . '%';
            $query->where(function ($q) use ($searchTerm) {
                $q->where('title', 'like', $searchTerm)
                  ->orWhere('description', 'like', $searchTerm);
            });
        }
        
        // Apply sorting
        $sortField = $request->input('sort_field', 'updated_at');
        $sortDirection = $request->input('sort_direction', 'desc');
        $query->orderBy($sortField, $sortDirection);
        
        // Paginate results
        $products = $query->paginate($request->input('per_page', 10));
        
        return ProductResource::collection($products);
    }
    
    /**
     * Display a listing of the resource for web views.
     *
     * @return \Illuminate\Http\Response
     */
    public function webIndex()
    {
        $products = Product::query()
            ->where('published', true)
            ->latest()
            ->get();
        
        return view('index', compact('products'));
    }

    /**
     * Display the specified resource.
     *
     * @param  string|int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Clean the ID
        $cleanId = $this->cleanProductId($id);
        
        // Find the product with the cleaned ID
        $product = Product::with('category')->findOrFail($cleanId);
        
        // Ensure published is always set
        if (!isset($product->published)) {
            $product->published = false;
        }

        // Add image URL if present
        if ($product->image) {
            $product->image_url = $this->getImageUrl($product->image);
        }

        return new ProductResource($product);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string|int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
{
    // Clean the ID
    $cleanId = $this->cleanProductId($id);
    
    // Log the incoming data for debugging
    Log::info('Product update request', [
        'product_id' => $cleanId,
        'title' => $request->input('title'),
        'all_data' => $request->all()
    ]);
    
    // Find the product
    $product = Product::findOrFail($cleanId);
    
    // Validate the request data
    $validated = $request->validate([
        'title' => 'required|string|max:2000',
        'description' => 'nullable|string',
        'price' => 'required|numeric|min:0',
        'published' => 'boolean',
        'track_inventory' => 'boolean',
        'quantity' => 'nullable|numeric|min:0',
        'reorder_level' => 'nullable|numeric|min:0',
        'category_id' => 'nullable|exists:categories,id', // Add this line
    ]);
    
    // Set boolean fields explicitly to avoid null values
    $validated['published'] = $request->boolean('published', false);
    $validated['track_inventory'] = $request->boolean('track_inventory', false);
    
    // Set user who updated the product
    if (auth()->check()) {
        $validated['updated_by'] = auth()->id();
    }
    
    // Update the product with validated data
    $product->update($validated);
    
    // Reload the product with the category relationship
    $product = $product->fresh(['category']);
    
    // Log the update for debugging
    Log::info('Product updated', [
        'product_id' => $product->id,
        'category_id' => $product->category_id,
        'has_category' => $product->category ? true : false
    ]);
    
    // Return the updated product as a resource
    return new ProductResource($product);
}
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  string|int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Clean the ID
        $cleanId = $this->cleanProductId($id);
        
        $product = Product::findOrFail($cleanId);
        $product->delete();
        
        return response()->noContent();
    }

  /**
 * Display the product view page
 *
 * @param  \App\Models\Product  $product
 * @return \Illuminate\Http\Response
 */
public function view(Product $product)
{
    // Get related products from the same category
    $relatedProducts = [];
    
    if ($product->category_id) {
        // Get up to 4 products from the same category (excluding the current product)
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('published', true)
            ->take(4)
            ->get();
    }
    
    // If we couldn't find 4 related products from the same category, get some random products
    if (count($relatedProducts) < 4) {
        $additionalProducts = Product::where('id', '!=', $product->id)
            ->where('published', true)
            ->inRandomOrder()
            ->take(4 - count($relatedProducts))
            ->get();
            
        // Merge the two collections
        $relatedProducts = $relatedProducts->concat($additionalProducts);
    }
    
    // Return the view with product and related products
    return view('sproduct', compact('product', 'relatedProducts'));
}

    /**
     * Display the shop page with all published products
     *
     * @return \Illuminate\Http\Response
     */
    public function shop()
    {
        $query = Product::where('published', true);
        
        $products = $query->latest()->get();
        
        return view('shop', compact('products'));
    }

    /**
     * Display the cart page
     *
     * @return \Illuminate\Http\Response
     */
    public function cart()
    {
        return view('cart.index');
    }
    

    /**
     * Display the blog page
     *
     * @return \Illuminate\Http\Response
     */
    public function blog()
    {
        return view('blog');
    }

    /**
     * Display the about page
     *
     * @return \Illuminate\Http\Response
     */
    public function about()
    {
        return view('about_us');
    }

    /**
     * Display the calorie calculator page
     *
     * @return \Illuminate\Http\Response
     */
    public function calorieCalculator()
    {
        return view('Calorie_Calculator');
    }

    /**
     * Display the contact page
     *
     * @return \Illuminate\Http\Response
     */
    public function contact()
    {
        return view('Contact_us');
    }
}