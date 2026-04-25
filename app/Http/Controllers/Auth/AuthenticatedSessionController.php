<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;
use App\Helpers\CartHelper;
use App\Models\Cart_item;
use Illuminate\Support\Facades\Cookie;


class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Login', [
            'canResetPassword' => Route::has('password.request'),
            'status' => session('status'),
        ]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // 👇 merge cookie cart into DB after login
        $cookieItems = CartHelper::getCookieCartItems();

        if (!empty($cookieItems)) {
            foreach ($cookieItems as $item) {
                $cartItem = Cart_item::where([
                    'user_id'    => $request->user()->id,
                    'product_id' => $item['product_id'],
                ])->first();

                if ($cartItem) {
                    // product already in DB cart — add quantities together
                    $cartItem->increment('quantity', $item['quantity']);
                } else {
                    // product not in DB cart — create new record
                    Cart_item::create([
                        'user_id'    => $request->user()->id,
                        'product_id' => $item['product_id'],
                        'quantity'   => $item['quantity'],
                    ]);
                }
            }

            // clear the cookie after merging
            Cookie::queue(Cookie::forget('cart_items'));
        }


        return redirect()->intended(route('home.index', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
