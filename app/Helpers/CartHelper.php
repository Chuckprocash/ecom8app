<?php

namespace App\Helpers;
use App\Models\Cart_item;
use App\Models\Product;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Arr;


class CartHelper
{
    /**
     * Add item to cart
     */
    
    public static function getCartCount() {
        if($user = auth()->user()){
            return Cart_item::whereUserId($user->id)->sum('quantity');
        }else{
            $items = self::getCookieCartItems();
            return self::getTotalCount($items);
        }
    }
    public static function getCartItems() {
        if($user = auth()->user()){
            return Cart_item::whereUserId($user->id)->get()->map(fn (Cart_item $item) => ['product_id' => $item->product_id, 'quantity' => $item->quantity]);
        }else{
            return self::getCookieCartItems();
        }

    }

    public static function getCookieCartItems() {
        // $cookieValue = request()->cookie('cart_items', '[]'); // default is empty JSON string
        // return json_decode($cookieValue, true) ?? [];
        return json_decode(request()->cookie('cart_items', '[]'), true);
    }
    public static function setCookieCartItems(array $items) {
        // Cookie::queue('cart_items', fn(int $carry, array $item) => $carry + $item['quantity'], 0);
        Cookie::queue('cart_items', json_encode($items), 60 * 24 * 7); // expires in 7 days
    }

    public static function getTotalCount(array $items): int
    {
        return array_reduce($items, fn(int $carry, array $item) => $carry + $item['quantity'], 0);
    }

    public static function saveCookieCartItems() {
        $user = auth()->user();
        $userCartItems = Cart_item::where(['user_id' => $user->id])->get()->keyBy('product_id');
        $savedCartItems = [];

        foreach(self::getCookieCartItems() as $cartItem){
            if(isset($userCartItems[$cartItem['product_id']])){
                $userCartItems[$cartItem['product_id']]->update(['quantity' => $cartItem['quantity']]);
                continue;
            }

            $savedCartItems[] = [
                'user_id' => $user->id,
                'product_id' => $cartItem['product_id'],
                'quantity' => $cartItem['quantity'],
            ];
        }

        if(!empty($savedCartItems)){
            Cart_item::insert($savedCartItems);       
        }
    }
    

    public static function moveCartItemsIntoDb() {

        $request = request();
        $cartItems = self::getCookieCartItems();
        $newCartItems = [];

        foreach($cartItems as $cartItem){
            //Checking if the reccord already exists in the database
            $existingCartItem = Cart_item::where([
                'user_id' => $request->user()->id,
                'product_id' => $cartItem['product_id'],
            ])->first();

            //only insert if it does not already exist
            if(!$existingCartItem){
                $newCartItems[] = [
                    'user_id' => $request->user()->id,
                    'product_id' => $cartItem['product_id'],
                    'quantity' => $cartItem['quantity'],
                ];
            }
        }

        //insert the new cart items
        if(!empty($newCartItems)){
            Cart_item::insert($newCartItems);
        }

    }

    

    public static function getProductsAndCartItems() {
        //
        $cartItems = self::getCartItems();
        $ids = Arr::pluck($cartItems, 'product_id');
        $products = Product::whereIn('id', $ids)->with('product_images')->get();
        //
        $cartItems = Arr::keyBy($cartItems, 'product_id');
        //
        return [$products, $cartItems];
    }

}