<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();
        // \App\Models\Category::factory(2)->create();
        // \App\Models\Product::factory(2)->create();

        // $categories = \App\Models\Category::all();

        // \App\Models\Product::all()->each(function ($product) use ($categories) {
        //     $product->categories()->attach(
        //         $categories->random(rand(1, 3))->pluck('id')->toArray()
        //     );
        // });

        // \App\Models\Auction::factory(5)->create();

        // \App\Models\CustomerBid::factory(2)->create();

        \App\Models\Interaction::factory(2)->create();


        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
