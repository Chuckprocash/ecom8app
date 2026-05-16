<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Inertia\Inertia;

class AdminController extends Controller
{
    public function index() {
        return Inertia::render('Admin/Dashboard/index');
    }
        
    public function salesIndex() {            
        $orders = Order::with(['order_items.product.product_images'])->with('payment')->paginate(15);
        return Inertia::render('Admin/Dashboard/Sales', compact('orders'));
    }
}
