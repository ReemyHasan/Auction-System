<?php

namespace Database\Factories;

use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Auction>
 */
class AuctionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $products = Product::all()->pluck('id')->toArray();
        $price = fake()->randomFloat(2, 0, 1000);
        $start = fake()->dateTimeBetween('-1 days', '+2 days');
        $end = fake()->dateTimeBetween($start, $start->format('Y-m-d H:i:s') . '+4 days');
        $status = (Carbon::now() > $end) ? 1 : 0;
        return [
            'product_id' => fake()->randomElement($products),
            'lowest_price' => $price - fake()->randomFloat(2, 0, $price),
            'closing_price' => $price + fake()->randomFloat(2, 0, 1000 - $price),
            'start_time' => $start->format('Y-m-d H:i:s'),
            'closing_time' => $end->format('Y-m-d H:i:s'),
            'status' => $status
        ];
    }
}
