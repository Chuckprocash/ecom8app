<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use App\Models\Product;


class Category extends Model
{
    //
    use HasSlug;

    protected $guarded = [];
    //
    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }
    //
    public function products() {
        return $this->hasMany(Product::class);
    }
}
