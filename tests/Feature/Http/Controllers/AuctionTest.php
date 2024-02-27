<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Auction;
use App\Models\CustomerBid;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuctionTest extends TestCase
{
    use RefreshDatabase;
    protected $vendor;
    protected $customer;
    protected $product;
    protected $auction;

    // protected function setUp(): void
    // {
    //     parent::setUp();
    //     $this->vendor = User::factory()->create(['type' => 1]);
    //     $this->customer = User::factory()->create(['type' => 2]);
    //     $this->product = Product::factory()->create(['vendor_id' => $this->vendor->id]);
    //     $start = fake()->dateTimeBetween('-2 days', '-1 days');
    //     $end = fake()->dateTimeBetween($start->format('Y-m-d H:i:s') . '+3 days', $start->format('Y-m-d H:i:s') . '+4 days');
    //     $this->auction = Auction::factory()->create([
    //         'start_time' => $start->format('Y-m-d H:i:s'),
    //         'closing_time' => $end->format('Y-m-d H:i:s'),
    //         'product_id' => $this->product->id
    //     ]);
    //     CustomerBid::create([
    //         "auction_id" => $this->auction->id,
    //         "price" => $this->auction->lowest_price,
    //         "customer_id" => $this->customer->id
    //     ]);
    // }
    // public function test_index(): void
    // {
    //     $auction = $this->auction;
    //     $response = $this->actingAs($this->vendor)->get('/auctions');
    //     $response->assertStatus(200);
    //     $response->assertViewHas('auctions', function ($collection) use ($auction) {
    //         return $collection->contains($auction);
    //     });
    // }
    // public function test_index_pagination(): void
    // {
    //     $auctions = Auction::factory(11)->create();
    //     $last = $auctions->last();
    //     $response = $this->actingAs($this->vendor)->get('/auctions');
    //     $response->assertStatus(200);
    //     $response->assertViewHas('auctions', function ($collection) use ($last) {
    //         return !$collection->contains($last);
    //     });
    // }

    // public function test_vendor_can_access_create_auction(): void
    // {
    //     $response = $this->actingAs($this->vendor)->get('/auctions/create');
    //     $response->assertStatus(200);
    // }
    // public function test_customer_cannot_access_create_auction(): void
    // {
    //     $response = $this->actingAs($this->customer)->get('/auctions/create');
    //     $response->assertStatus(403);
    // }
    // public function test_store_auction(): void
    // {
    //     $auction = [
    //         "product_id" => $this->product->id,
    //         "lowest_price" => "10",
    //         "closing_price" => "100",
    //         "start_time" => Carbon::now(),
    //         "closing_time" => Carbon::now(),
    //     ];
    //     $response = $this->from('auctions')->actingAs($this->vendor)->post('auctions', $auction);
    //     $response->assertStatus(302);
    //     $response->assertRedirectToRoute('auctions.index');
    //     $this->assertDatabaseHas('auctions', $auction);
    // }
    // public function test_validation_error_store_auction(): void
    // {
    //     $auction = [
    //         "product_id" => $this->product->id,
    //         "lowest_price" => "10000",
    //         "closing_price" => "100",
    //         "start_time" => Carbon::now(),
    //         "closing_time" => Carbon::now(),
    //     ];
    //     $response = $this->from('auctions/create')->actingAs($this->vendor)->post('auctions', $auction);
    //     $response->assertStatus(302);
    //     $response->assertSessionHasErrors(['closing_price' => 'The closing price field must be greater than 10000.']);
    //     $response->assertRedirect('auctions/create');
    // }
    // public function test_get_auction_by_id(): void
    // {
    //     $response = $this->actingAs($this->vendor)->get('auctions/' . $this->auction->id);
    //     $response->assertStatus(200);
    //     $response->assertViewHas('auction', $this->auction);
    // }
    // public function test_auction_not_found(): void
    // {
    //     $response = $this->actingAs($this->vendor)->get('auctions/' . 10000);
    //     $response->assertStatus(404);
    // }
    // public function test_can_access_edit_auction_page_with_existed_auction(): void
    // {
    //     $response = $this->actingAs($this->vendor)->get('auctions/' . $this->auction->id . '/edit');
    //     $response->assertStatus(200);
    //     $response->assertViewHas('auction', $this->auction);
    // }

    // public function test_cannot_access_edit_auction_page_with_existed_auction(): void
    // {
    //     $response = $this->actingAs($this->customer)->get('auctions/' . $this->auction->id . '/edit');
    //     $response->assertStatus(403);
    // }

    // public function test_can_update_auction(): void
    // {
    //     $fields = ['closing_time' => Carbon::now()];
    //     $response = $this->from('auctions')
    //         ->actingAs($this->vendor)->put('auctions/' . $this->auction->id, $fields);
    //     $response->assertStatus(302);
    //     $response->assertRedirectToRoute("auctions.index");
    // }
    // public function test_error_update_auction(): void
    // {
    //     $fields = ['start_time' => Carbon::now()];
    //     $response = $this->from('auctions/' . $this->auction->id . '/edit')
    //         ->actingAs($this->vendor)->put('auctions/' . $this->auction->id, $fields);
    //     $response->assertStatus(302);
    //     $response->assertSessionHasErrors(['start_time' => 'The start time field must be a date before or equal to closing time.']);
    //     $response->assertRedirect('auctions/' . $this->auction->id . '/edit');

    // }

    // public function test_can_delete_auction(): void
    // {
    //     $response = $this->from('auctions')
    //         ->actingAs($this->vendor)->delete('auctions/' . $this->auction->id);
    //     $response->assertStatus(302);
    //     $response->assertRedirectToRoute("auctions.index");
    // }
    // public function test_cannot_delete_auction(): void
    // {
    //     $response = $this->from('auctions')
    //         ->actingAs($this->vendor)->delete('auctions/' . rand(1, 100));
    //     $response->assertStatus(404);
    // }

    // public function test_can_access_to_add_interaction_to_auction_view()
    // {
    //     $response = $this->actingAs($this->customer)->get('auctions/' . $this->auction->id . '/add_interaction');
    //     $response->assertStatus(200);
    //     $response->assertViewIs('auctions.add_interactions');
    //     $response->assertViewHas('auction', $this->auction);

    // }
    // public function test_cannot_access_add_interaction_to_product_unauthorized_access()
    // {
    //     $customer = User::factory()->create(['type' => 2]);
    //     $response = $this->actingAs($customer)->get('auctions/' . $this->auction->id . '/add_interaction');
    //     $response->assertStatus(403);

    // }
    // public function test_cannot_access_add_multiple_interaction_to_auction()
    // {


    //     $this->auction->interactions()->create([
    //         'rate' => 4,
    //         'comment' => 'comment',
    //         'user_id' => $this->customer->id,
    //     ]);
    //     $response = $this->actingAs($this->customer)->get('auctions/' . $this->auction->id . '/add_interaction');
    //     $response->assertStatus(403);

    // }
    // public function test_can_store_interaction_to_auction()
    // {
    //     $response = $this->from('auctions')
    //         ->actingAs($this->customer)->post(
    //             'auctions/' . $this->auction->id . '/store_interaction',
    //             [
    //                 'rate' => 4,
    //                 'comment' => 'comment'
    //             ]
    //         );
    //     $response->assertStatus(302);
    //     $response->assertRedirectToRoute('auctions.index');
    //     $response->assertSessionHasNoErrors();

    // }
    // public function test_cannot_store_interaction_to_auction()
    // {

    //     $response = $this->from('auctions')
    //         ->actingAs($this->vendor)->post(
    //             'auctions/' . $this->auction->id . '/store_interaction',
    //             [
    //                 'rate' => 4,
    //                 'comment' => 'comment'
    //             ]
    //         );
    //     $response->assertStatus(403);

    // }
}
