<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::create([
            'name' => 'Premium',
            'slug' => 'premium',
        ]);
        Category::create([
            'name' => 'Business',
            'slug' => 'business',
        ]);
        Category::create([
            'name' => 'Local',
            'slug' => 'loal',
        ]);
    }
}
