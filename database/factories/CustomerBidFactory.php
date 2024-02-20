<?php

namespace Database\Factories;

use App\Models\Auction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CustomerBid>
 */
class CustomerBidFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $customers = User::where('type',2)->orWhere('type',3)->pluck('id')->toArray();
        $auctions = Auction::all()->where('closing_time','>',Carbon::now())->where('start_time','<',Carbon::now());
        $auction = fake()->randomElement($auctions);
        return [
            'customer_id' => fake()->randomElement($customers),
            'auction_id' => $auction->id,
            'price' => fake()->randomFloat(2, $auction->lowest_price, $auction->closing_price-1)
        ];
    }
}
