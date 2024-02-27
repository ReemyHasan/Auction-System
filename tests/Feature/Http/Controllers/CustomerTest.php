<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Auction;
use App\Models\CustomerBid;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerTest extends TestCase
{
    use RefreshDatabase;
    protected $vendor;
    protected $customer;
    protected $product;
    protected $auction;
    protected $bid;
    protected function setUp(): void
    {
        parent::setUp();
        $this->vendor = User::factory()->create(['type' => 1]);
        $this->customer = User::factory()->create(['type' => 2]);
        $this->product = Product::factory()->create(['vendor_id' => $this->vendor->id]);
        $start = fake()->dateTimeBetween('-2 days', '-1 days');
        $end = fake()->dateTimeBetween($start->format('Y-m-d H:i:s') . '+3 days', $start->format('Y-m-d H:i:s') . '+4 days');
        $this->auction = Auction::factory()->create([
            'start_time' => $start->format('Y-m-d H:i:s'),
            'closing_time' => $end->format('Y-m-d H:i:s'),
            'product_id' => $this->product->id
        ]);
        $this->bid = CustomerBid::factory()->create([
            'auction_id' => $this->auction->id,
            "price" => $this->auction->lowest_price,
            "customer_id" => $this->customer->id
        ]);
    }
    public function test_get_customer_auctions(): void
    {
        $auction = $this->auction;
        $response = $this->actingAs($this->customer)->get('customers/'.$this->customer->id.'/auctions');
        $response->assertStatus(200);
        $response->assertViewHas('auctions',function($collection) use ($auction){
            return $collection[0]->id == $auction->id;
        });
    }

    public function test_get_customer_auction_bids(): void
    {
        $bid = $this->bid;
        $response = $this->actingAs($this->customer)->get('customers/'.$this->customer->id.'/auctions/'.$this->auction->id.'/bids');
        $response->assertStatus(200);
        $response->assertViewHas('auction',$this->auction);
        $response->assertViewHas('bids',function($collection) use ($bid){
                    return $collection->contains($bid);
                });

    }
}
