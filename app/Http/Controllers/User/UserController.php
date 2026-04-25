<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Application;
use Inertia\Inertia;
use App\Models\Product;
use App\Models\User_address;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    public function index() {

        $products = Product::with('product_images', 'category', 'brand')->latest()->limit(8)->get();
        return Inertia::render('User/index', [
            'canLogin' => app('router')->has('login'),
            'canRegister' => app('router')->has('register'),
            'laravelVersion' => Application::VERSION,
            'phpVersion' => PHP_VERSION,
            'products' => $products,
        ]);

    }

    public function updateShippingAddress(Request $request) {
        $validated = $request->validate([
            'type'         => 'required|string|max:50',
            'address1'     => 'required|string|max:255',
            'address2'     => 'nullable|string|max:255',
            'city'         => 'required|string|max:255',
            'state'        => 'nullable|string|max:50',
            'zipcode'      => 'required|string|max:50',
            'country_code' => 'required|string|max:3',
            'isMain'       => 'boolean',
        ]);

        $user = $request->user();


        // always create a new address
        $address = User_address::create([
            ...$validated,
            'user_id' => $user->id,
        ]);

        if (!$address) {
            return redirect()->back()->with('error', 'Failed to save address.');
        }

        return redirect()->back()->with('success', 'Shipping address updated successfully.');
    }
    
}
