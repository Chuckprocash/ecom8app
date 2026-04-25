<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\Cart_item;
use App\Models\User_address;
use App\Helpers\CartHelper;
use Inertia\Inertia;

class CartController extends Controller
{
    
    public function show() {
        $user = Auth::user();
        if($user){
            $userAddress = User_address::where('user_id', $user->id)->latest()->first();

            return Inertia::render('User/CartList', compact('userAddress','user'));
        }
        
        return Inertia::render('User/CartList');
    }
    //
    public function store(Request $request, Product $product) {
        //requested user id
        $user = $request->user();
        // $quantity = $request->post('quantity', 1);
        $quantity = $request->input('quantity', 1);
        //
        if($user){
            $cart_item = Cart_item::where(['user_id' => $user->id, 'product_id' => $product->id])->first();
            //
            if($cart_item){
                $cart_item->increment('quantity');
            }else{
                Cart_item::create([
                    'user_id' => $user->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                ]);
            }
        //
        }else{
            $cart_items = CartHelper::getCookieCartItems();
            $productExists = false;
            //
            foreach($cart_items as &$item){
                if($item['product_id'] == $product->id){
                    $item['quantity'] += $quantity;
                    $productExists = true;
                    break;
                }
            }
            unset($item); // always unset reference after loop

            if(!$productExists){
                $cart_items[] = [
                    'user_id' => null,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'price' => $product->price,
                ];

            }
            CartHelper::setCookieCartItems($cart_items);
        }
        //
        return redirect()->back()->with('success', 'cart item added successfully.');
    }

    //
    public function update(Request $request, Product $product) {

        $user = $request->user();
        $quantity = $request->integer('quantity');
        // dd($quantity);
        if($user){
            Cart_item::where(['user_id' => $user->id, 'product_id' => $product->id])->update(['quantity' => $quantity]);
        }else{
            $cart_items = CartHelper::getCookieCartItems();
            // dd($cart_items)
            foreach($cart_items as &$item){
                if($item['product_id'] == $product->id){
                    $item['quantity'] = $quantity;
                    $productExists = true;
                    break;
                }
            }
            unset($item);
            CartHelper::setCookieCartItems($cart_items); //cart_items
        }

        return redirect()->back()->with('success', 'Cart Updated Successfully');

    }
    //
    public function destroy(Request $request, Product $product) {

        $user = $request->user();
        //
        if($user){
            Cart_item::where(['user_id' => $user->id, 'product_id' => $product->id])->first()?->delete();
            if(Cart_item::count() <= 0){
                return redirect()->route('/')->with('info', 'Your Cart is Empty!');
            }else{
                return redirect()->back()->with('success', 'Item removed successfully.');
            }
        //
        }else{
            $cart_items = CartHelper::getCookieCartItems();
            foreach($cart_items as $i => &$item){
                if($item['product_id'] == $product->id){
                    array_splice($cart_items, $i, 1);
                    break;
                }
            }

            CartHelper::setCookieCartItems($cart_items);

            if(Cart_item::count() <= 0){
                return redirect()->route('/')->with('info', 'Your Cart is Empty!');
            }else{
                return redirect()->back()->with('success', 'Item removed successfully.');
            }
        } 

    }

}
