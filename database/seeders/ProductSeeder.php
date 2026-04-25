<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\User;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    
    public function run(): void
    {
        $admin = User::where('isAdmin', 1)->first();
        
        Product::create([
            'title' => 'Mysterious Product',
            'quantity' => '3',
            'price' => '10.50',
            'category_id' => '1',
            'brand_id' => '1',
            'description' => 'lorem ipsum dolor sit emet boa noite...',
            'created_by' => $admin->id, 
        ]);
    }
}
