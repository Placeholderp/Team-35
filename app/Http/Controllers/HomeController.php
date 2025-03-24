<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    /**
     * Display the home page with products by categories.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Get banners if they exist
        $banners = Banner::where('is_active', true)->take(3)->get();
        
        // Get all active categories
        $categories = Category::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();
        
        // Get featured products (newest 8 published products)
        $featuredProducts = Product::where('published', true)
            ->with('category')
            ->latest()
            ->take(8)
            ->get();
            
        // Initialize array to hold category products
        $categoryProducts = [];
        
        // Get products for each category (up to 4 per category)
        foreach ($categories as $category) {
            $products = Product::where('published', true)
                ->where('category_id', $category->id)
                ->with('category')
                ->latest()
                ->take(4)
                ->get();
                
            // Only add categories that have products
            if ($products->count() > 0) {
                $categoryProducts[$category->id] = [
                    'category' => $category,
                    'products' => $products
                ];
            }
        }
        
        // Log info about what we're returning to the view
        Log::info('Home page data', [
            'categories_count' => $categories->count(),
            'featured_count' => $featuredProducts->count(),
            'category_products_count' => count($categoryProducts),
        ]);
        
        return view('index', compact(
            'banners',
            'categories',
            'featuredProducts',
            'categoryProducts'
        ));
    }
}