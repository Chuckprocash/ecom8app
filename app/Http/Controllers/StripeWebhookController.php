<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Webhook;
use Stripe\Exception\SignatureVerificationException;
use App\Models\Order; 
use App\Models\Payment; 
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class StripeWebhookController extends Controller
{
    
    public function handleWebhook(Request $request)
    {
        $payload = $request->getContent();
        $sig_header = $request->header('Stripe-Signature');
        $endpoint_secret = config('services.stripe.webhook_secret');

        try {
            $event = Webhook::constructEvent($payload, $sig_header, $endpoint_secret);
        } catch (\UnexpectedValueException $e) {
            // Invalid payload
            Log::error('Stripe Webhook: Invalid payload', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Invalid payload'], 400);
        } catch (SignatureVerificationException $e) {
            // Invalid signature
            Log::error('Stripe Webhook: Invalid signature', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        // Handle the event
        switch ($event->type) {
            case 'checkout.session.completed':
                $session = $event->data->object;
                $this->handleCheckoutSessionCompleted($session);
                break;

            case 'payment_intent.succeeded':
                $paymentIntent = $event->data->object;
                $this->handlePaymentIntentSucceeded($paymentIntent);
                break;

            case 'payment_intent.payment_failed':
                $paymentIntent = $event->data->object;
                $this->handlePaymentFailed($paymentIntent);
                break;

            default:
                Log::info('Stripe Webhook: Unhandled event type', ['type' => $event->type]);
        }

        return response()->json(['status' => 'success'], 200);
    }

    private function handleCheckoutSessionCompleted($session)
    {
        Log::info('Checkout session completed', ['session_id' => $session->id]);

        // Update your order/payment record
        $order = Order::where('session_id', $session->id)->first();
        if(!$order){throw new NotFoundHttpException();}
        if($order->status == 'unpaid'){
            $order->status = 'paid';
            $order->save();
        }
        //Update payment database
        $payment = Payment::where('order_id', $order->id)->first();
        if(!$payment){throw new NotFoundHttpException();}
        if($payment->status == 'pending'){
            $payment->status = 'paid';
            $payment->save();
        }
        // 
    }

    private function handlePaymentIntentSucceeded($paymentIntent)
    {
        Log::info('Payment intent succeeded', ['payment_intent_id' => $paymentIntent->id]);
        
        // Additional logic if needed

    }

    private function handlePaymentFailed($paymentIntent)
    {
        Log::info('Payment intent failed', ['payment_intent_id' => $paymentIntent->id]);
        
        // Handle failed payment
        $order = Order::where('session_id', $session->id)->first();
    }

}
