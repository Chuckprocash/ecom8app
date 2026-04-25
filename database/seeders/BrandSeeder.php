<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Brand;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Brand::create([
            'name' => 'Silver Castle',
            'slug' => 'silvercastle',
        ]);
        Brand::create([
            'name' => 'Favor Castle',
            'slug' => 'favorcastle',
        ]);
        Brand::create([
            'name' => 'Clan Castle',
            'slug' => 'clancastle',
        ]);
    }
}
