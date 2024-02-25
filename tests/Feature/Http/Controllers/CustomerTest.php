<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Auction;
use App\Models\CustomerBid;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CustomerTest extends TestCase
{
    use RefreshDatabase;
    private function creatUsers()
    {
        return User::factory(10)->create();
    }
    public function test_get_customer_auctions(): void
    {
        $vendor = User::factory()->create(['type' => 1]);
        $customer = User::factory()->create(['type' => 2]);
        $product = Product::factory()->create(['vendor_id' => $vendor->id]);
        $start = fake()->dateTimeBetween('-2 days', '-1 days');
        $end = fake()->dateTimeBetween($start->format('Y-m-d H:i:s') . '+3 days', $start->format('Y-m-d H:i:s') . '+4 days');
        $auction = Auction::factory()->create([
            'start_time' => $start->format('Y-m-d H:i:s'),
            'closing_time' => $end->format('Y-m-d H:i:s'),
            'product_id' => $product->id
        ]);
        CustomerBid::create([
            "auction_id"=> $auction->id,
            "price"=> $auction->lowest_price,
            "customer_id"=>$customer->id
        ]);
        $response = $this->actingAs($customer)->get('customers/'.$customer->id.'/auctions');
        $response->assertStatus(200);
        $response->assertViewHas('auctions',function($collection) use ($auction){
            return $collection[0]->id == $auction->id;
        });
    }

    public function test_get_customer_auction_bids(): void
    {
        $vendor = User::factory()->create(['type' => 1]);
        $customer = User::factory()->create(['type' => 2]);
        $product = Product::factory()->create(['vendor_id' => $vendor->id]);
        $start = fake()->dateTimeBetween('-2 days', '-1 days');
        $end = fake()->dateTimeBetween($start->format('Y-m-d H:i:s') . '+3 days', $start->format('Y-m-d H:i:s') . '+4 days');
        $auction = Auction::factory()->create([
            'start_time' => $start->format('Y-m-d H:i:s'),
            'closing_time' => $end->format('Y-m-d H:i:s'),
            'product_id' => $product->id
        ]);
        $bid = CustomerBid::create([
            "auction_id"=> $auction->id,
            "price"=> $auction->lowest_price,
            "customer_id"=>$customer->id
        ]);
        $response = $this->actingAs($customer)->get('customers/'.$customer->id.'/auctions/'.$auction->id.'/bids');
        $response->assertStatus(200);
        $response->assertViewHas('auction',$auction);
        $response->assertViewHas('bids',function($collection) use ($bid){
                    return $collection->contains($bid);
                });

    }
}
