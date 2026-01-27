<?php

namespace Database\Seeders;

use App\Models\Categories;
use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Electronics', 'slug' => 'electronics'],
            ['name' => 'Fashion', 'slug' => 'fashion'],
            ['name' => 'Home & Kitchen', 'slug' => 'home'],
            ['name' => 'Sports & Outdoors', 'slug' => 'sports'],
            ['name' => 'Books', 'slug' => 'books'],
            ['name' => 'Health & Beauty', 'slug' => 'health-beauty'],
            ['name' => 'Toys & Games', 'slug' => 'toys-games'],
            ['name' => 'Automotive', 'slug' => 'automotive'],
        ];
        
        foreach ($categories as $Categories) {
            Categories::create($Categories);
        }
    }
}