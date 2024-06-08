<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\FoodItem;

class FoodItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     *  @return void
    */
    public function run(): void
    {
        FoodItem::create(['name' => 'Pizza', 'price' => 10.99, 'restaurant_id' => 1]);
        FoodItem::create(['name' => 'Burger', 'price' => 8.99, 'restaurant_id' => 1]);
        // Add more items as needed
    }
}
