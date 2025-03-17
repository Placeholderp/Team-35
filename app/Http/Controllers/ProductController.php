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
 * Display the cart page
 *
 * @return \Illuminate\Http\Response
 */
public function cart()
{
    return view('cart');
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
    
        return view('index', compact('products'));
    }

    /**
     * Display the product view page
     *
     * @param  Product  $product
     * @return \Illuminate\Http\Response
     */
    public function view(Product $product)
    {
        return view('product', compact('product'));
    }

    /**
     * Display the shop page with all published products
     *
     * @return \Illuminate\Http\Response
     */
    public function shop()
    {
        $products = Product::where('published', true)->get();
        return view('shop', compact('products'));
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