<?php

namespace Database\Seeders;

use App\Models\Categories;
use Illuminate\Database\Seeder;
use App\Models\Products;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get Categories IDs
        $electronicsId = Categories::where('slug', 'electronics')->first()->id;
        $fashionId = Categories::where('slug', 'fashion')->first()->id;
        $homeId = Categories::where('slug', 'home')->first()->id;
        $sportsId = Categories::where('slug', 'sports')->first()->id;
        
        $products = [
            // Electronics
            [
                'name' => 'Wireless Bluetooth Headphones',
                'description' => 'Premium noise-cancelling wireless headphones with 30-hour battery life.',
                'price' => 199.99,
                'stock' => 25,
                'category_id' => $electronicsId,
                'product_image' => 'products/headphones.jpg'
            ],
            [
                'name' => 'Smart Watch Pro',
                'description' => 'Advanced smartwatch with health monitoring and GPS tracking.',
                'price' => 299.99,
                'stock' => 15,
                'category_id' => $electronicsId,
                'product_image' => 'products/smartwatch.jpg'
            ],
            [
                'name' => 'Laptop 15" Ultrabook',
                'description' => 'Lightweight laptop with 16GB RAM and 512GB SSD storage.',
                'price' => 899.99,
                'stock' => 8,
                'category_id' => $electronicsId,
                'product_image' => 'products/laptop.jpg'
            ],
            [
                'name' => 'Wireless Earbuds',
                'description' => 'True wireless earbuds with charging case and 24-hour battery.',
                'price' => 79.99,
                'stock' => 50,
                'category_id' => $electronicsId,
                'product_image' => 'products/earbuds.jpg'
            ],
            
            // Fashion
            [
                'name' => 'Leather Jacket',
                'description' => 'Genuine leather jacket with classic design and comfortable fit.',
                'price' => 149.99,
                'stock' => 12,
                'category_id' => $fashionId,
                'product_image' => 'products/jacket.jpg'
            ],
            [
                'name' => 'Running Shoes',
                'description' => 'Lightweight running shoes with cushioning and breathable material.',
                'price' => 89.99,
                'stock' => 30,
                'category_id' => $fashionId,
                'product_image' => 'products/shoes.jpg'
            ],
            [
                'name' => 'Casual T-Shirt',
                'description' => '100% cotton t-shirt with modern fit and various colors available.',
                'price' => 24.99,
                'stock' => 100,
                'category_id' => $fashionId,
                'product_image' => 'products/tshirt.jpg'
            ],
            [
                'name' => 'Designer Handbag',
                'description' => 'Elegant handbag with multiple compartments and premium finish.',
                'price' => 249.99,
                'stock' => 6,
                'category_id' => $fashionId,
                'product_image' => 'products/handbag.jpg'
            ],
            
            // Home & Kitchen
            [
                'name' => 'Coffee Maker',
                'description' => 'Programmable coffee maker with thermal carafe and auto-shutoff.',
                'price' => 79.99,
                'stock' => 20,
                'category_id' => $homeId,
                'product_image' => 'products/coffeemaker.jpg'
            ],
            [
                'name' => 'Air Fryer',
                'description' => 'Digital air fryer with multiple cooking presets and large capacity.',
                'price' => 129.99,
                'stock' => 18,
                'category_id' => $homeId,
                'product_image' => 'products/airfryer.jpg'
            ],
            [
                'name' => 'Bed Sheet Set',
                'description' => 'Egyptian cotton bed sheet set with 1000 thread count.',
                'price' => 69.99,
                'stock' => 35,
                'category_id' => $homeId,
                'product_image' => 'products/bedsheets.jpg'
            ],
            [
                'name' => 'Robot Vacuum',
                'description' => 'Smart robot vacuum with mapping technology and app control.',
                'price' => 299.99,
                'stock' => 10,
                'category_id' => $homeId,
                'product_image' => 'products/vacuum.jpg'
            ],
            
            // Sports & Outdoors
            [
                'name' => 'Yoga Mat',
                'description' => 'Non-slip yoga mat with carrying strap and alignment markers.',
                'price' => 34.99,
                'stock' => 40,
                'category_id' => $sportsId,
                'product_image' => 'products/yogamat.jpg'
            ],
            [
                'name' => 'Camping Tent',
                'description' => '4-person waterproof camping tent with easy setup design.',
                'price' => 149.99,
                'stock' => 8,
                'category_id' => $sportsId,
                'product_image' => 'products/tent.jpg'
            ],
            [
                'name' => 'Dumbbell Set',
                'description' => 'Adjustable dumbbell set with storage rack and ergonomic handles.',
                'price' => 199.99,
                'stock' => 12,
                'category_id' => $sportsId,
                'product_image' => 'products/dumbbells.jpg'
            ],
            [
                'name' => 'Bicycle',
                'description' => 'Mountain bike with 21-speed gear system and suspension fork.',
                'price' => 399.99,
                'stock' => 5,
                'category_id' => $sportsId,
                'product_image' => 'products/bicycle.jpg'
            ],
        ];
        
        foreach ($products as $product) {
            Products::create($product);
        }
    }
}