<?php

namespace App\Helpers;

use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Support\Arr;

/**
 * Class Cart
 *
 * Helper class for handling cart-related operations.
 */
class Cart
{
    /**
     * Get the total count of items in the cart.
     */
    public static function getCartItemsCount(): int
    {
        $request = \request();
        $user = $request->user();

        if ($user) {
            // If user is authenticated, sum up the quantity of cart items from the DB.
            return CartItem::where('user_id', $user->id)->sum('quantity');
        } else {
            // Otherwise, retrieve cart items stored in a cookie.
            $cartItems = self::getCookieCartItems();

            return array_reduce(
                $cartItems,
                fn($carry, $item) => $carry + $item['quantity'],
                0
            );
        }
    }

    /**
     * Get cart items.
     */
    public static function getCartItems()
    {
        $request = \request();
        $user = $request->user();

        if ($user) {
            // Retrieve cart items for the authenticated user from the database
            // and map each item to an array with product_id and quantity.
            return CartItem::where('user_id', $user->id)->get()->map(
                fn($item) => ['product_id' => $item->product_id, 'quantity' => $item->quantity]
            );
        } else {
            // Retrieve cart items from the cookie for guest users.
            return self::getCookieCartItems();
        }
    }

    /**
     * Retrieve cart items stored in the cookie.
     */
    public static function getCookieCartItems()
    {
        $request = \request();
        // Decode the JSON from the 'cart_items' cookie, or return an empty array if none.
        return json_decode($request->cookie('cart_items', '[]'), true);
    }

    /**
     * Calculate the total count from a given set of cart items.
     */
    public static function getCountFromItems($cartItems)
    {
        return array_reduce(
            $cartItems,
            fn($carry, $item) => $carry + $item['quantity'],
            0
        );
    }

    /**
     * Move cart items from cookie to the database upon user login.
     */
    public static function moveCartItemsIntoDb()
    {
        $request = \request();
        $cartItems = self::getCookieCartItems();
        // Get existing cart items for the user from the database, keyed by product_id.
        $dbCartItems = CartItem::where(['user_id' => $request->user()->id])->get()->keyBy('product_id');
        $newCartItems = [];
        
        // Loop through each cart item from the cookie.
        foreach ($cartItems as $cartItem) {
            // If the item already exists in the user's DB cart, skip it.
            if (isset($dbCartItems[$cartItem['product_id']])) {
                continue;
            }
            // Otherwise, prepare the item for insertion into the database.
            $newCartItems[] = [
                'user_id' => $request->user()->id,
                'product_id' => $cartItem['product_id'],
                'quantity' => $cartItem['quantity'],
            ];
        }

        // Insert new cart items into the database if there are any.
        if (!empty($newCartItems)) {
            CartItem::insert($newCartItems);
        }
    }

    /**
     * Retrieve products associated with the cart items along with the cart items themselves.
     */
    public static function getProductsAndCartItems(): array|\Illuminate\Database\Eloquent\Collection
    {
        $cartItems = self::getCartItems();
        $ids = Arr::pluck($cartItems, 'product_id');
        $products = Product::query()->whereIn('id', $ids)->get();
        $cartItems = Arr::keyBy($cartItems, 'product_id');

        return [$products, $cartItems];
    }
}
