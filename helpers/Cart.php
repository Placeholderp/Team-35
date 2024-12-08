<?php

namespace  App\Helpers;

use app\models\CartItem;


/********************************
Developer: Georgi Tsarev
University ID: 230132296
Helper class for the Cart Controller
********************************/


class Cart 
{
    // Gets the number of items in the cart in the navigation cart
    public static function get_cart_items_count(): int {
        $request = \request();
        $user = $request->user();
        if ($user) {
            return CartItem::where('userID', $user->id)->sum('stock');
        } else {
            $cartItems = self::get_cookie_cart_items();
            return array_reduce($cartItems, fn($carry, $item,) => $carry + $item['stock']);
        }

    }

    // Returns the cart items that are currently in the cookie if the user is not authorised in the db
    public static function get_cookie_cart_items() {
        $request = \request();
        return json_decode($request->cookie(cartItems, '[]'), true);
    }

    // Returns an array of all the cart items
    public static function get_cart_items() {
        $request = \request();
        $user = $request->user();
        if ($user) {
            return CartItem::where('userID', $user->id)->get()->map(fn($item)=>['ProductID'=>$item->productID, 'stock'=>$item->stock]);
        } else {
            return self::get_cookie_cart_items();
        }
    }
    
// Takes the cart items the user has in the cookie and moves them into the DB
public static function move_cart_into_db() {
    $request = \request();
    $cartItems = self::get_cookie_cart_items();
    $dbCart = cartItem::where(['userID'=>$request->user()->id]->get()->keyBy('ProductID'));
    $newCart = [];
    foreach ($cartItems as $cartItem) {
        if (isset($dbCart[$cartItem['ProductID']])) {
            continue; }
        $newCart[] = ['userID'=>$request->user()->id, 'ProductID'=>$cartItem['ProductID'], 'stock'=>$cartItem['stock']];
    }

    if (!empty($newCart)) {
        CartItem::insert($newCart);
    }
}
    // Returns the count of the cart items 
    public static function get_count_from_items($cartItems) {
        return array_reduce($cartItems, fn($carry, $item)=>$carry + $item['stock']);
    }
}
