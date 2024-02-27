<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Auction;
use App\Models\CustomerBid;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;

class AuctionTest extends CommonTests
{
    protected $product;
    protected $auction;

    protected function setUp(): void
    {
        parent::setUp();
        $this->product = Product::factory()->create(['vendor_id' => $this->vendor->id]);
        $start = fake()->dateTimeBetween('-2 days', '-1 days');
        $end = fake()->dateTimeBetween($start->format('Y-m-d H:i:s') . '+3 days', $start->format('Y-m-d H:i:s') . '+4 days');
        $this->auction = Auction::factory()->create([
            'start_time' => $start->format('Y-m-d H:i:s'),
            'closing_time' => $end->format('Y-m-d H:i:s'),
            'product_id' => $this->product->id
        ]);
        CustomerBid::create([
            "auction_id" => $this->auction->id,
            "price" => $this->auction->lowest_price,
            "customer_id" => $this->customer->id
        ]);
    }
    public function test_index(): void
    {
        $auction = $this->auction;
        $this->index($auction,'auctions');
    }
    public function test_index_pagination(): void
    {
        $auctions = Auction::factory(11)->create();
        $this->index_pagination($auctions,'auctions');

    }

    public function test_vendor_can_access_create_auction(): void
    {
        $this->vendor_can_access_create('/auctions/create');
    }
    public function test_customer_cannot_access_create_auction(): void
    {
        $this->customer_cannot_access_create('/auctions/create');

    }
    public function test_store_auction(): void
    {
        $auction = [
            "product_id" => $this->product->id,
            "lowest_price" => "10",
            "closing_price" => "100",
            "start_time" => Carbon::now(),
            "closing_time" => Carbon::now(),
        ];
        $this->store_success($auction,'auctions');

    }
    public function test_validation_error_store_auction(): void
    {
        $auction = [
            "product_id" => $this->product->id,
            "lowest_price" => "10000",
            "closing_price" => "100",
            "start_time" => Carbon::now(),
            "closing_time" => Carbon::now(),
        ];
        $errorMessage = ['closing_price' => 'The closing price field must be greater than 10000.'];
        $this->validation_error_store($auction,'auctions/create','auctions',$errorMessage);
    }
    public function test_get_auction_by_id(): void
    {
        $this->get_by_id($this->auction,'auctions/','auction');
    }
    public function test_auction_not_found(): void
    {
        $this->not_found('auctions/');
    }
    public function test_can_access_edit_auction_page_with_existed_auction(): void
    {
        $response = $this->can_access_edit_page_with_existed_item($this->auction,'auctions/');
        $response->assertViewHas('auction', $this->auction);
    }

    public function test_cannot_access_edit_auction_page_with_existed_auction(): void
    {
        $this->cannot_access_edit_page_with_unauthorized_user('auctions/' . $this->auction->id . '/edit');
    }

    public function test_can_update_auction(): void
    {
        $fields = ['closing_time' => Carbon::now()];
        $response = $this->from('auctions')
            ->actingAs($this->vendor)->put('auctions/' . $this->auction->id, $fields);
        $response->assertStatus(302);
        $response->assertRedirectToRoute("auctions.index");
    }
    public function test_error_update_auction(): void
    {
        $fields = ['start_time' => Carbon::now()];
        $response = $this->from('auctions/' . $this->auction->id . '/edit')
            ->actingAs($this->vendor)->put('auctions/' . $this->auction->id, $fields);
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['start_time' => 'The start time field must be a date before or equal to closing time.']);
        $response->assertRedirect('auctions/' . $this->auction->id . '/edit');

    }

    public function test_can_delete_auction(): void
    {
        $response = $this->from('auctions')
            ->actingAs($this->vendor)->delete('auctions/' . $this->auction->id);
        $response->assertStatus(302);
        $response->assertRedirectToRoute("auctions.index");
    }
    public function test_cannot_delete_auction(): void
    {
        $response = $this->from('auctions')
            ->actingAs($this->vendor)->delete('auctions/' . rand(1, 100));
        $response->assertStatus(404);
    }

    public function test_can_access_to_add_interaction_to_auction_view()
    {
        $response = $this->actingAs($this->customer)->get('auctions/' . $this->auction->id . '/add_interaction');
        $response->assertStatus(200);
        $response->assertViewIs('auctions.add_interactions');
        $response->assertViewHas('auction', $this->auction);

    }
    public function test_cannot_access_add_interaction_to_product_unauthorized_access()
    {
        $customer = User::factory()->create(['type' => 2]);
        $response = $this->actingAs($customer)->get('auctions/' . $this->auction->id . '/add_interaction');
        $response->assertStatus(403);

    }
    public function test_cannot_access_add_multiple_interaction_to_auction()
    {


        $this->auction->interactions()->create([
            'rate' => 4,
            'comment' => 'comment',
            'user_id' => $this->customer->id,
        ]);
        $response = $this->actingAs($this->customer)->get('auctions/' . $this->auction->id . '/add_interaction');
        $response->assertStatus(403);

    }
    public function test_can_store_interaction_to_auction()
    {
        $response = $this->from('auctions')
            ->actingAs($this->customer)->post(
                'auctions/' . $this->auction->id . '/store_interaction',
                [
                    'rate' => 4,
                    'comment' => 'comment'
                ]
            );
        $response->assertStatus(302);
        $response->assertRedirectToRoute('auctions.index');
        $response->assertSessionHasNoErrors();

    }
    public function test_cannot_store_interaction_to_auction()
    {

        $response = $this->from('auctions')
            ->actingAs($this->vendor)->post(
                'auctions/' . $this->auction->id . '/store_interaction',
                [
                    'rate' => 4,
                    'comment' => 'comment'
                ]
            );
        $response->assertStatus(403);

    }
}
