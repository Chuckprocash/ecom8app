<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product_image;
use Illuminate\Support\Facades\Storage;

class ProductImagesController extends Controller
{
    public function destroy(Product_image $image) 
    {
        // Delete physical file
        if ($image->image && Storage::disk('public')->exists($image->image)) {
            Storage::disk('public')->delete($image->image);
        }
        
        // Delete database record
        $image->delete();
        
        return redirect()->back()->with('success', 'Image deleted successfully');
    }

    public function store(Request $request, $productId)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048'
        ]);

        $path = $request->file('image')->store('product_images', 'public');
        
        Product_image::create([
            'product_id' => $productId,
            'image' => $path
        ]);

        return back()->with('success', 'Image uploaded successfully');
    }
}
