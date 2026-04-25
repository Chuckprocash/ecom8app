<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User_address;
use App\Models\Order;
use App\Models\Order_item;
use App\Models\Cart_item;
use App\Models\Payment;
use Inertia\Inertia;

class CheckoutController extends Controller
{
    
    public function store(Request $request) {
        // dd($request->products);
        
        $products = $request->products;
        $checkoutList = [];
        foreach($products as $product){
            $checkoutList[] = [
                'price_data' => [
                'currency' => 'usd',
                'product_data' => [
                    'name' => $product['product']['title'],
                ],
                'unit_amount' => (int) ($product['product']['price'] * 100),
                ],
                'quantity' => $product['quantity'],
            ];
        }

        //dd($checkoutList);

        $stripe = new \Stripe\StripeClient(env('STRIPE_API_KEY'));

        $checkout_session = $stripe->checkout->sessions->create([
            'line_items' => $checkoutList,
            'mode' => 'payment',
            'success_url' => route('checkout.success').'?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('checkout.cancel'),
        ]);

        //header("HTTP/1.1 303 See Other");
        //header("Location: " . $checkout_session->url);
        // dd($checkout_session);

        $user = $request->user();

        // reset all previous main addresses
        User_address::where('user_id', $user->id)
            ->where('isMain', 1)
            ->update(['isMain' => 0]);

        //Find in the address model the address passed by $request->address and update isMain to 1
        $address = User_address::where('id', $request->address['id'])
            ->where('user_id', $user->id)
            ->first();
        $address->update(['isMain' => 1]);

        try{
        DB::beginTransaction();
        //create an order
        $order = new Order();
        $order->status = 'unpaid';
        $order->total_price = $request->total;
        $order->session_id = $checkout_session->id;
        $order->created_by = $user->id;
        $order->user_address_id = $address->id;
        $order->save();

        //create the order items
        foreach($products as $item){
            Order_item::create([
                'order_id' => $order->id,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['product']['price'],
            ]);
            $cartItem = Cart_item::where('product_id', $item['product_id'])->where('user_id', $user->id)->first();
            $cartItem->delete();
        }

        //create payment database
        $paymentData = [
            'order_id' => $order->id,
            'amount' => $request->total,
            'status' => 'pending',
            'type' => 'stripe',
            'created_by' => $user->id,
            'updated_by' => $user->id,
        ];

        Payment::create($paymentData);
        DB::commit(); // everything succeeded, make it permanent

        }catch (\Exception $e) {
            DB::rollBack(); // something failed, revert all changes
            \Log::error('Checkout failed: ' . $e->getMessage());
            return redirect()->back()->with('error', $e->getMessage());
        }
        //redirect to stripe 
        
        return Inertia::location($checkout_session->url);

    }


    //
    public function stripeSuccess(Request $request){
        //
        \Stripe\Stripe::setApiKey(env('STRIPE_API_KEY'));
        $sessionId = $request->get('session_id');
        
        try{
        //
        $session = \Stripe\Checkout\Session::retrieve($sessionId);
        if(!$session){throw new NotFoundHttpException();}
        //
        $order = Order::where('session_id', $session->id)->first();
        if(!$order){throw new NotFoundHttpException();}
        if($order->status == 'unpaid'){
            $order->status = 'paid';
            $order->save();
        }
        //
        return redirect()->route('dashboard');

        }catch(\Exception $e) {
            throw new NotFoundHttpException();
        }


    }
    public function stripeCancel(){
        //
    }

    public function checkoutForOrder(Request $request, Order $order) {
        $user = $request->user();
        // dd($order);
        // security check — make sure the order belongs to the user
        if ($order->created_by !== $user->id) {
            abort(403);
        }

        // make sure order is still unpaid
        if ($order->status !== 'unpaid') {
            return redirect()->back()->with('error', 'This order has already been paid.');
        }

        try {
            \Stripe\Stripe::setApiKey(env('STRIPE_API_KEY'));

            $orderItems = $order->order_items()->with('product')->get();

            $checkoutList = [];
            foreach ($orderItems as $item) {
                $checkoutList[] = [
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => [
                            'name' => $item->product->title,
                        ],
                        'unit_amount' => (int) ($item->unit_price * 100),
                    ],
                    'quantity' => $item->quantity,
                ];
            }

            $checkout_session = \Stripe\Checkout\Session::create([
                'line_items'  => $checkoutList,
                'mode'        => 'payment',
                'success_url' => route('checkout.success') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url'  => route('checkout.cancel'),
            ]);

            // update the order with the new session id
            $order->update(['session_id' => $checkout_session->id]);

            return Inertia::location($checkout_session->url);

        } catch (\Exception $e) {
            \Log::error('Checkout for order failed: ' . $e->getMessage());
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

}
