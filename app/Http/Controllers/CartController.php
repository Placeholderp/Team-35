<?php

namespace App\Http\Controllers;

use App\Helpers\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cookie;

/********************************
Developer: Georgi Tsarev
University ID: 230132296
Class acts as the controller for the cart functionality
********************************/

class CartController extends Controller
{
    public function index()
    {
        $cartItems = Cart::get_cart_items();
        $ids = Arr::pluck($cartItems, 'ProductID');
        $products = Product::query()->whereIn('id', $ids)->get();
        $cartItems = Arr::keyBy($cartItems, 'ProductID');
        $total = 0;

        foreach ($products as $product) {
            $total += $product->price * $cartItems[$product->id]['stock'];
        }

        return view('cart.index', compact('cartItems', 'products', 'total'));
    }
    // Updates the stock of the item in the cart
    public function update_stock(Request $request, Product $p) {
        $stock = (int) $request->post('stock');
        $user = $request->user();

        if ($user) {
            CartItem::where(['UserID' => $user->id, 'ProductID' => $p->id])
                ->update(['stock' => $stock]);

            return response(['number' => Cart::get_cart_items_count()]);
        } else {
            $cartItems = json_decode($request->cookie('cartItems', '[]'), true);

            foreach ($cartItems as &$item) {
                if ($item['ProductID'] == $p->id) {
                    $item['stock'] = $stock;
                    break;
                }
            }

            Cookie::queue('cartItems', json_encode($cartItems), 55 * 21 * 30);
        }
    }
    // Adds an item to the Cart and updates the count
    public function add(Request $request, Product $p) {
        $stock = $request->post('stock', 1);
        $user = $request->user();

        if ($user) {
            $cartItem = CartItem::where(['UserID' => $user->id, 'ProductID' => $p->id])->first();

            if ($cartItem) {
                $cartItem->stock += $stock;
                $cartItem->update();
            } else {
                CartItem::create([
                    'UserID' => $user->id,
                    'ProductID' => $p->id,
                    'stock' => $stock,
                ]);
            }

            return response(['number' => Cart::get_cart_items_count()]);
        } else {
            $cartItems = json_decode($request->cookie('cartItems', '[]'), true);
            $foundProduct = false;

            foreach ($cartItems as &$item) {
                if ($item['ProductID'] == $p->id) {
                    $item['stock'] += $stock;
                    $foundProduct = true;
                    break;
                }
            }

            if (!$foundProduct) {
                $cartItems[] = ['UserID' => null, 'ProductID' => $p->id, 'stock' => $stock];
            }

            Cookie::queue('cartItems', json_encode($cartItems), 21 * 21 * 30); 
        }
    }
    // Removes an item from the cart and updates the count
    public function remove(Request $request, Product $p) {
        $user = $request->user();

        if ($user) {
            $cartItem = CartItem::query()->where(['UserID' => $user->id, 'ProductID' => $p->id])->first();

            if ($cartItem) {
                $cartItem->delete();
            }
            return response(['count' => Cart::get_cart_items_count()]);
        } else {
            $cartItems = json_decode($request->cookie('cartItems', '[]'), true);

            foreach ($cartItems as $index => $item) {
                if ($item['ProductID'] == $p->id) {
                    array_splice($cartItems, $index, 1);
                    break;
                }
            }
            Cookie::queue('cartItems', json_encode($cartItems), 55 * 21 * 30);

            return response(['number' => Cart::get_count_from_items($cartItems)]);
        }
    }


}