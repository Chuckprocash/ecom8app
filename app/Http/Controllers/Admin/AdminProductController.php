<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Product;
use App\Models\Product_image;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Support\Facades\Storage;

class AdminProductController extends Controller
{
    
    public function index() {
        // dd('ok!');
        $categories = Category::all();
        $brands = Brand::all();
        $products = Product::with(['category', 'brand', 'product_images'])->latest()->paginate(10);
        return Inertia::render('Admin/Product/index', compact('products', 'categories', 'brands'));
    } 

    public function show(Product $product){
        $product->load('category', 'brand', 'product_images');
        return Inertia::render('Admin/Product/ShowProduct', compact('product'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'  => 'required|min:3',
            'brand_id' => 'required',
            'category_id' => 'required',
            'price'  => 'required',
            'quantity'  => 'nullable',
            'description' => 'required',
            'images'    => 'nullable|array',
            'images.*'  => 'image|mimes:jpeg,png,jpg,webp|max:2048'
        ]);

        $product = Product::create($validated);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imagePath = $image->store('images', 'public');

                Product_image::create([
                    'product_id' => $product->id,
                    'image' => $imagePath,
                ]);
            }
        }

        return redirect()->back()->with('success', 'Product created!');
    }

    public function update(Request $request, Product $product) {
        $validated = $request->validate([
            'title'  => 'required|min:3',
            'brand_id' => 'required',
            'category_id' => 'required',
            'price'  => 'required',
            'quantity'  => 'nullable',
            'description' => 'required',
            'images'    => 'nullable|array',
            'images.*'  => 'image|mimes:jpeg,png,jpg,webp|max:2048'
        ]);
        //
        $product->update($validated);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imagePath = $image->store('images', 'public');

                Product_image::create([
                    'product_id' => $product->id,
                    'image' => $imagePath,
                ]);
            }
        }

        return redirect()->back()->with('success', 'Product Updated!');
    }

    public function destroy($id)
    {
        try {
            $product = Product::findOrFail($id);

            // Delete associated images from storage
            foreach ($product->product_images as $productImage) {
                if (Storage::exists('public/' . $productImage->image)) {
                    Storage::delete('public/' . $productImage->image);
                }
            }

            // Delete image records from DB then delete product
            $product->product_images()->delete();
            $product->delete();

            return redirect()->back()->with('success', 'Product deleted successfully.');

        } catch (ModelNotFoundException $e) {
            return redirect()->back()->with('error', 'Product not found.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete product. Please try again.');
        }
    }


}
