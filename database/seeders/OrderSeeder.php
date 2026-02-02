<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        // Get existing users and products
        $users = DB::table('users')->pluck('id')->toArray();
        $products = DB::table('products')->select('id', 'price')->get();
        
        if (empty($users) || empty($products)) {
            $this->command->info('No users or products found. Please seed users and products first.');
            return;
        }
        
        // Sample delivery information templates
        $deliveryTemplates = [
            [
                'name' => 'John Doe',
                'phone' => '555-123-4567',
                'address' => '123 Main Street',
                'city' => 'New York',
                'state' => 'NY',
                'country' => 'USA',
                'postal_code' => '10001'
            ],
            [
                'name' => 'Jane Smith',
                'phone' => '555-987-6543',
                'address' => '456 Oak Avenue',
                'city' => 'Los Angeles',
                'state' => 'CA',
                'country' => 'USA',
                'postal_code' => '90001'
            ],
            [
                'name' => 'Robert Johnson',
                'phone' => '555-456-7890',
                'address' => '789 Pine Road',
                'city' => 'Chicago',
                'state' => 'IL',
                'country' => 'USA',
                'postal_code' => '60601'
            ]
        ];
        
        // Status options
        $statuses = ['Pending', 'On Delivery', 'Completed', 'Cancelled'];
        
        // Create 50 orders
        for ($i = 1; $i <= 50; $i++) {
            $userId = $users[array_rand($users)];
            $orderNumber = $this->generateOrderNumber();
            $status = $statuses[array_rand($statuses)];
            $deliveryInfo = json_encode($deliveryTemplates[array_rand($deliveryTemplates)]);
            
            // Create order
            $orderId = DB::table('orders')->insertGetId([
                'user_id' => $userId,
                'order_number' => $orderNumber,
                'status' => $status,
                'total_amount' => 0, // Will update after adding items
                'delivery_information' => $deliveryInfo,
                'created_at' => now()->subDays(rand(0, 90))->subHours(rand(0, 23)),
                'updated_at' => now()
            ]);
            
            // Add random items to order (1-5 items per order)
            $totalAmount = 0;
            $numItems = rand(1, 5);
            $selectedProducts = $products->random($numItems);
            
            foreach ($selectedProducts as $product) {
                $quantity = rand(1, 3);
                $subtotal = $product->price * $quantity;
                $totalAmount += $subtotal;
                
                DB::table('order_item')->insert([
                    'order_id' => $orderId,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
            
            // Update order total amount
            DB::table('orders')
                ->where('id', $orderId)
                ->update(['total_amount' => $totalAmount]);
            
            // Update status for cancelled orders to be older
            if ($status === 'Cancelled') {
                DB::table('orders')
                    ->where('id', $orderId)
                    ->update([
                        'created_at' => now()->subDays(rand(30, 90)),
                        'updated_at' => now()->subDays(rand(20, 30))
                    ]);
            }
        }
        
        $this->command->info('50 orders created successfully!');
    }
    
    private function generateOrderNumber(): string
    {
        return 'ORD-' . date('Ymd') . '-' . strtoupper(Str::random(6));
    }
}