<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Product_image;


class Product extends Model
{
    use HasSlug;

     protected $fillable = [
        'title',
        'slug',
        'description',
        'price',
        'quantity',
        'category_id',
        'brand_id',
        'created_by',
    ];

    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }


    public function category() {
        return $this->belongsTo(Category::class);
    }
    public function brand() {
       return $this->belongsTo(Brand::class);
    }
    public function product_images() {
        return $this->hasMany(Product_image::class);
    }

    //Filter Logic for prices, categories, and brands

    public function scopeFiltered( Builder $query ) {
        //
        $query
        ->when(request('brands'), function (Builder $q) {
            $q->whereIn('brand_id', request('brands'));
        })
        ->when(request('categories'), function (Builder $q) {
            $q->whereIn('category_id', request('categories'));
        })
        ->when(request('prices'), function (Builder $q) {
            $q->whereBetween('price', [
                request('prices.from', 0),
                request('prices.to', 1000000),
            ]);
        });
    }




}
