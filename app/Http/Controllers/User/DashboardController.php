<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    //
    public function index() {
        $user = auth()->user();
        $orders = $user->orders()
        ->with(['order_items.product.product_images'])  // Eager load items and their products
        ->latest()                        // Most recent first
        ->get();
        return Inertia::render('User/Dashboard', compact('orders'));
    }
}
