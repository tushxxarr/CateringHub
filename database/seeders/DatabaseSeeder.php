<?php

namespace Database\Seeders;

use App\Models\FoodCategory;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        $categories = [
            ['name' => 'Main Course', 'description' => 'Hearty main dishes for lunch or dinner.'],
            ['name' => 'Appetizer', 'description' => 'Small bites to start your meal.'],
            ['name' => 'Dessert', 'description' => 'Sweet treats and after-meal goodies.'],
            ['name' => 'Beverage', 'description' => 'Refreshing drinks to accompany your meal.'],
        ];

        foreach ($categories as $category) {
            FoodCategory::create($category);
        }
    }
}
