<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Helpers\CartHelper;
use App\Models\Product;
use App\Http\Resources\ProductResource;


class CartResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        
        [$products, $cartItems] = $this->resource;

        return [
            'count' => CartHelper::getCartCount(),
            'total' => $products->reduce(fn (?float $carry, Product $product) => $carry + $product->price * $cartItems[$product->id]['quantity']),
            'items' => $cartItems,
            'products' => ProductResource::collection($products),
        ];

        // return parent::toArray($request);
    }
}
