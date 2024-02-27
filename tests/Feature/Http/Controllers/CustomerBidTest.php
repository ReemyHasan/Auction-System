<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Auction;
use App\Models\CustomerBid;
use App\Models\Product;

class CustomerBidTest extends CommonTests
{
    protected $product;
    protected $runningAuction;
    protected $closedAuction;
    protected $bid;
    protected function setUp(): void
    {
        parent::setUp();
        $this->product = Product::factory()->create(['vendor_id' => $this->vendor->id]);
        $start = fake()->dateTimeBetween('-2 days', '-1 days');
        $end = fake()->dateTimeBetween($start->format('Y-m-d H:i:s') . '+3 days', $start->format('Y-m-d H:i:s') . '+4 days');
        $this->runningAuction = Auction::factory()->create([
            'start_time' => $start->format('Y-m-d H:i:s'),
            'closing_time' => $end->format('Y-m-d H:i:s'),
            'product_id' => $this->product->id
        ]);
        $this->bid = CustomerBid::factory()->create([
            'auction_id' => $this->runningAuction->id,
            "price" => $this->runningAuction->lowest_price,
            "customer_id" => $this->customer->id
        ]);
        $closedStart = fake()->dateTimeBetween('-20 days', '-10 days');
        $ClosedEnd = fake()->dateTimeBetween($start->format('Y-m-d H:i:s'), $start->format('Y-m-d H:i:s') . '+1 days');
        $this->closedAuction = Auction::factory()->create([
            'start_time' => $closedStart->format('Y-m-d H:i:s'),
            'closing_time' => $ClosedEnd->format('Y-m-d H:i:s'),
            'product_id' => $this->product->id
        ]);
        CustomerBid::factory()->create([
            'auction_id' => $this->closedAuction->id,
            "price" => $this->closedAuction->lowest_price,
            "customer_id" => $this->customer->id
        ]);
    }
    public function test_bids_index(): void
    {
        $bid = $this->bid;
        $this->index($bid,'bids');
    }
    public function test_bids_index_pagination(): void
    {
        $bids = CustomerBid::factory(11)->create(['auction_id' => $this->runningAuction->id]);
        $this->index_pagination($bids,'bids');

    }
    public function test_show_auction_bids()
    {
        $response = $this->actingAs($this->customer)->get('auctions/' . $this->runningAuction->id . '/bids');
        $response->assertStatus(200);
        $response->assertViewHas('customers');
        $response->assertViewHas('bids');
    }
    public function test_auction_bids_page_does_not_exist()
    {
        $response = $this->actingAs($this->customer)->get('auctions/1000/bids');
        $response->assertStatus(404);
    }

    public function test_store_bid_to_auction()
    {
        $response = $this->from('auctions/' . $this->runningAuction->id . '/bids')
            ->actingAs($this->customer)->post('auctions/' . $this->runningAuction->id . '/bids/store', [
                    "auction_id" => $this->runningAuction->id,
                    "price" => $this->runningAuction->lowest_price + 1,
                ]);
        $response->assertStatus(302);
        $response->assertRedirect('auctions/' . $this->runningAuction->id . '/bids');
    }

    public function test_cannot_store_bid_to_auction_unauthorized_user()
    {
        $response = $this->from('auctions/' . $this->runningAuction->id . '/bids')
            ->actingAs($this->vendor)->post('auctions/' . $this->runningAuction->id . '/bids/store', [
                    "auction_id" => $this->runningAuction->id,
                    "price" => $this->runningAuction->lowest_price,
                ]);
        $response->assertStatus(403);
    }
    public function test_cannot_add_bids_to_closed_auction()
    {
        $response = $this->from('auctions/' . $this->closedAuction->id . '/bids')
            ->actingAs($this->customer)->post(
                'auctions/' . $this->closedAuction->id . '/bids/store',
                [
                    "auction_id" => $this->closedAuction->id,
                    "price" => $this->closedAuction->lowest_price,
                ]
            );
        $response->assertStatus(302);
        $response->assertRedirect('auctions/' . $this->closedAuction->id . '/bids');
        $response->assertSessionHas(['error']);

    }

    public function test_delete_my_latest_bid_auction()
    {
        $response = $this->from('auctions/' . $this->runningAuction->id . '/bids')
            ->actingAs($this->customer)->delete('customers/' . $this->customer->id . '/auctions/' . $this->runningAuction->id . '/bids');
        $response->assertStatus(302);
        $response->assertRedirect('auctions/' . $this->runningAuction->id . '/bids');
    }
    public function test_cannot_delete_my_latest_bid_auction()
    {
        $response = $this->from('auctions/' . $this->closedAuction->id . '/bids')
            ->actingAs($this->customer)->delete('customers/' . $this->customer->id . '/auctions/' . $this->closedAuction->id . '/bids');
        $response->assertStatus(302);
        $response->assertRedirect('auctions/' . $this->closedAuction->id . '/bids');
        $response->assertSessionHas(['error']);
    }
    public function test_leave_auction()
    {
        $response = $this->from('customers/' . $this->customer->id . '\/auctions/' . $this->runningAuction->id . '/bids')
            ->actingAs($this->customer)->get('customers/' . $this->customer->id . '\/auctions/' . $this->runningAuction->id);
        $response->assertStatus(302);
        $response->assertRedirect('customers/' . $this->customer->id . '\/auctions/' . $this->runningAuction->id . '/bids');
    }
}
