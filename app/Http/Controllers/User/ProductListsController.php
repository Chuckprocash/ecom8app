<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product; 
use App\Models\Category; 
use App\Models\Brand; 
use Inertia\Inertia;
use App\Http\Resources\ProductResource;

class ProductListsController extends Controller
{
    
    public function index() {

        $products = Product::with('product_images', 'category', 'brand');
        //
        $filteredProducts = $products->filtered()->paginate(9);
        //
        if(request()->hasAny(['brands', 'categories', 'prices'])){
            $filteredProducts->withQueryString();
        }
        return Inertia::render('User/ProductList', [
            'products' => ProductResource::collection($filteredProducts),
            'brands'   => Brand::all(),
            'categories' => Category::all(),
        ]);        
    }

}
