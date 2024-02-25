<?php

namespace Database\Factories;

use App\Models\Auction;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Interaction>
 */
class InteractionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $rand = rand(0, 1);
        if ($rand == 0) {
            $users = User::where("type", 1)->orWhere('type', 3)->pluck('id')->toArray();
            $products = Product::all();
            $product = fake()->randomElement($products);
            return [
                'rate' => rand(1, 5),
                'comment' => fake()->sentence,
                'user_id' =>fake()->randomElement($users),
                'interactionable_id'=> $product->id,
                'interactionable_type'=> get_class($product),

            ];
        } else {
            $auctions = Auction::where('start_time', '<', Carbon::now())->get();
            $auction = fake()->randomElement($auctions);
            $users = $auction->getAuctionCustomers()->pluck('id')->toArray();
            return [
                'rate' => rand(1, 5),
                'comment' => fake()->sentence,
                'user_id' =>fake()->randomElement($users),
                'interactionable_id'=> $auction->id,
                'interactionable_type'=> get_class($auction),
            ];
        }
    }
}
