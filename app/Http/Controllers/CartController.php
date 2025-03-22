<?php

namespace App\Http\Controllers;

use App\Helpers\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cookie;

class CartController extends Controller
{
    /**
     * Display the shopping cart view.
     */
    public function index()
    {

        list($products, $cartItems) = Cart::getProductsAndCartItems();

        $total = 0;

        foreach ($products as $product) {
            $total += $product->price * $cartItems[$product->id]['quantity'];
        }
        return view('cart', compact('cartItems', 'products', 'total'));
    }

    /**
     * Add a product to the shopping cart.
     */
    public function add(Request $request, Product $product)
    {
  
        $quantity = $request->post('quantity', 1);
        $user = $request->user();
        
        if ($user) {

            $cartItem = CartItem::where(['user_id' => $user->id, 'product_id' => $product->id])->first();

            if ($cartItem) {
  
                $cartItem->quantity += $quantity;
                $cartItem->update();
            } else {

                $data = [
                    'user_id' => $request->user()->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                ];
                CartItem::create($data);
            }

            return response([
                'count' => Cart::getCartItemsCount()
            ]);
        } else {
          
            $cartItems = json_decode($request->cookie('cart_items', '[]'), true);
            $productFound = false;

            foreach ($cartItems as &$item) {
                if ($item['product_id'] === $product->id) {
                    $item['quantity'] += $quantity;
                    $productFound = true;
                    break;
                }
            }
 
            if (!$productFound) {
                $cartItems[] = [
                    'user_id' => null,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'price' => $product->price
                ];
            }

            Cookie::queue('cart_items', json_encode($cartItems), 60 * 24 * 30);

            return response(['count' => Cart::getCountFromItems($cartItems)]);
        }
    }

    /**
     * Remove a product from the shopping cart.
     */
    public function remove(Request $request, Product $product)
    {
        $user = $request->user();
        if ($user) {
            // Find the cart item for the authenticated user.
            $cartItem = CartItem::query()->where(['user_id' => $user->id, 'product_id' => $product->id])->first();
            if ($cartItem) {
                // Delete the cart item if found.
                $cartItem->delete();
            }

            // Return the updated cart count.
            return response([
                'count' => Cart::getCartItemsCount(),
            ]);
        } else {

            $cartItems = json_decode($request->cookie('cart_items', '[]'), true);

            foreach ($cartItems as $i => &$item) {
                if ($item['product_id'] === $product->id) {
                    array_splice($cartItems, $i, 1);
                    break;
                }
            }
            // Update the cookie with the new cart items.
            Cookie::queue('cart_items', json_encode($cartItems), 60 * 24 * 30);

            // Return the updated cart count.
            return response(['count' => Cart::getCountFromItems($cartItems)]);
        }
    }

    /**
     * Update the quantity of a product in the shopping cart.
     */
    public function updateQuantity(Request $request, Product $product)
    {
        // Retrieve the new quantity from the request.
        $quantity = (int)$request->post('quantity');
        $user = $request->user();
        
        if ($user) {
            // Update the quantity for the cart item in the database.
            CartItem::where(['user_id' => $request->user()->id, 'product_id' => $product->id])
                ->update(['quantity' => $quantity]);

            // Return the updated cart items count.
            return response([
                'count' => Cart::getCartItemsCount(),
            ]);
        } else {

            $cartItems = json_decode($request->cookie('cart_items', '[]'), true);

            foreach ($cartItems as &$item) {
                if ($item['product_id'] === $product->id) {
                    $item['quantity'] = $quantity;
                    break;
                }
            }

            Cookie::queue('cart_items', json_encode($cartItems), 60 * 24 * 30);

            return response(['count' => Cart::getCountFromItems($cartItems)]);
        }
    }
}
