<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Auction;
use App\Models\CustomerBid;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Session;
use Tests\TestCase;

class CustomerBidTest extends TestCase
{
    use RefreshDatabase;
    private function creatUsers()
    {
        return User::factory(10)->create();
    }
    public function test_bids_index(): void
    {
        $users = $this->creatUsers();
        $product = Product::factory(10)->create();
        $auction = Auction::factory(5)->create();
        $bid = CustomerBid::factory()->create();
        $user = $users->last();
        $response = $this->actingAs($user)->get('/bids');
        $response->assertStatus(200);
        $response->assertViewHas('bids', function ($collection) use ($bid) {
            return $collection->contains($bid);
        });
    }
    public function test_bids_index_pagination(): void
    {
        $users = $this->creatUsers();
        $product = Product::factory(10)->create();
        $auction = Auction::factory(3)->create();
        $bids = CustomerBid::factory(11)->create();
        $last = $bids->last();
        $user = $users->last();
        $response = $this->actingAs($user)->get('/bids');
        $response->assertStatus(200);
        $response->assertViewHas('bids', function ($collection) use ($last) {
            return !$collection->contains($last);
        });
    }
    public function test_show_auction_bids()
    {
        $users = $this->creatUsers();
        $product = Product::factory(10)->create();
        $auction = Auction::factory()->create();
        $bids = CustomerBid::factory(11)->create(['auction_id' => $auction->id]);
        $response = $this->actingAs($users->last())->get('auctions/' . $auction->id . '/bids');
        $response->assertStatus(200);
        $response->assertViewHas('customers');
        $response->assertViewHas('bids');
    }
    public function test_auction_bids_page_does_not_exist()
    {
        $users = $this->creatUsers();
        $response = $this->actingAs($users->last())->get('auctions/1000/bids');
        $response->assertStatus(404);
    }

    public function test_store_bid_to_auction()
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
        $response = $this->from('auctions/' . $auction->id . '/bids')
            ->actingAs($customer)->post('auctions/' . $auction->id . '/bids/store', [
                    "auction_id" => $auction->id,
                    "price" => $auction->lowest_price,
                ]);
        $response->assertStatus(302);
        $response->assertRedirect('auctions/' . $auction->id . '/bids');
    }

    public function test_cannot_store_bid_to_auction_unauthorized_user()
    {
        $vendor = User::factory()->create(['type' => 1]);
        $customer = User::factory()->create(['type' => 1]);
        $product = Product::factory()->create(['vendor_id' => $vendor->id]);
        $start = fake()->dateTimeBetween('-2 days', '-1 days');
        $end = fake()->dateTimeBetween($start->format('Y-m-d H:i:s') . '+3 days', $start->format('Y-m-d H:i:s') . '+4 days');
        $auction = Auction::factory()->create([
            'start_time' => $start->format('Y-m-d H:i:s'),
            'closing_time' => $end->format('Y-m-d H:i:s'),
            'product_id' => $product->id
        ]);
        $response = $this->from('auctions/' . $auction->id . '/bids')
            ->actingAs($customer)->post('auctions/' . $auction->id . '/bids/store', [
                    "auction_id" => $auction->id,
                    "price" => $auction->lowest_price,
                ]);
        $response->assertStatus(403);
    }
    public function test_cannot_add_bids_to_closed_auction()
    {
        $vendor = User::factory()->create(['type' => 1]);
        $customer = User::factory()->create(['type' => 2]);
        $product = Product::factory()->create(['vendor_id' => $vendor->id]);
        $start = fake()->dateTimeBetween('-20 days', '-10 days');
        $end = fake()->dateTimeBetween($start->format('Y-m-d H:i:s'), $start->format('Y-m-d H:i:s') . '+1 days');
        $auction = Auction::factory()->create([
            'start_time' => $start->format('Y-m-d H:i:s'),
            'closing_time' => $end->format('Y-m-d H:i:s'),
            'product_id' => $product->id
        ]);
        $response = $this->from('auctions/' . $auction->id . '/bids')
            ->actingAs($customer)->post('auctions/' . $auction->id . '/bids/store', [
                    "auction_id" => $auction->id,
                    "price" => $auction->lowest_price,
                ]);
        $response->assertStatus(302);
        $response->assertRedirect('auctions/' . $auction->id . '/bids');
        $response->assertSessionHas(['error']);

    }

    public function test_delete_my_latest_bid_auction()
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
        $response = $this->from('auctions/' . $auction->id . '/bids')
            ->actingAs($customer)->delete('customers/'.$customer->id.'/auctions/' . $auction->id . '/bids');
        $response->assertStatus(302);
        $response->assertRedirect('auctions/' . $auction->id . '/bids');
    }
    public function test_cannot_delete_my_latest_bid_auction()
    {
        $vendor = User::factory()->create(['type' => 1]);
        $customer = User::factory()->create(['type' => 2]);
        $product = Product::factory()->create(['vendor_id' => $vendor->id]);
        $start = fake()->dateTimeBetween('-20 days', '-10 days');
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
        $response = $this->from('auctions/' . $auction->id . '/bids')
            ->actingAs($customer)->delete('customers/'.$customer->id.'/auctions/' . $auction->id . '/bids');
        $response->assertStatus(302);
        $response->assertRedirect('auctions/' . $auction->id . '/bids');
        $response->assertSessionHas(['error']);
    }
    public function test_leave_auction()
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
        CustomerBid::create([
            "auction_id"=> $auction->id,
            "price"=> ($auction->lowest_price+10),
            "customer_id"=>$customer->id
        ]);
        $response = $this->from('customers/'.$customer->id.'\/auctions/' . $auction->id . '/bids')
            ->actingAs($customer)->get('customers/'.$customer->id.'\/auctions/' . $auction->id);
        $response->assertStatus(302);
        $response->assertRedirect('customers/'.$customer->id.'\/auctions/' . $auction->id . '/bids');
    }
}
