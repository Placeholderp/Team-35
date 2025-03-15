<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

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
     * Display the specified resource.
     *
     * @param  string|int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Clean the ID
        $cleanId = $this->cleanProductId($id);
        
        $product = Product::findOrFail($cleanId);
        return response()->json($product);
    }

    public function index()
    {
        $products = Product::query()
            ->where('published', true)
            ->latest()
            ->get();
    
        return view('welcome', compact('products'));
    }
    /**
     * Update the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string|int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Clean the ID
        $cleanId = $this->cleanProductId($id);
        
        $product = Product::findOrFail($cleanId);
        // Update product logic...
        
        return response()->json($product);
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
    
}