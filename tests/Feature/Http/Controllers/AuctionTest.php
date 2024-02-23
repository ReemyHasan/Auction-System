<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Auction;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuctionTest extends TestCase
{
    use RefreshDatabase;
    // public function test_index(): void
    // {
    //     $users = User::factory(5)->create();
    //     Product::factory()->create();
    //     $auction = Auction::factory()->create();
    //     $categories = Category::factory(10)->create();
    //     $user = $users->last();
    //     $response = $this->actingAs($user)->get('/auctions');
    //     $response->assertStatus(200);
    //     $response->assertViewHas('auctions',function($collection) use ($auction){
    //         return $collection->contains($auction);
    //     });
    //     $response->assertViewHas('categories',$categories);
    // }
    // public function test_index_pagination(): void
    // {
    //     $users = User::factory(11)->create();
    //     Product::factory(11)->create();
    //     $auctions = Auction::factory(11)->create();
    //     $last = $auctions->last();
    //     $user = $users->last();
    //     $response = $this->actingAs($user)->get('/auctions');
    //     $response->assertStatus(200);
    //     $response->assertViewHas('auctions',function($collection) use ($last){
    //         return !$collection->contains($last);
    //     });
    // }

    //     public function test_vendor_can_access_create_auction(): void
    // {
    //     $user = User::factory()->create(['type' => 1]); //1 for vendor
    //     $response = $this->actingAs($user)->get('/auctions/create');
    //     $response->assertStatus(200);
    // }
    // public function test_customer_cannot_access_create_auction(): void
    // {
    //     $user = User::factory()->create(['type'=>'2']); //2 for customer
    //     $response = $this->actingAs($user)->get('/auctions/create');
    //     $response->assertStatus(403);
    // }
    // public function test_store_auction(): void
    // {
    //     $user = User::factory()->create(['type' => 1]);
    //     $product = Product::factory()->create(['vendor_id'=>$user->id]);
    //     $auction = [
    //         "product_id"=> $product->id,
    //         "lowest_price"=> "10",
    //         "closing_price"=> "100",
    //         "start_time"=> Carbon::now(),
    //         "closing_time"=> Carbon::now(),
    //     ];
    //     $response = $this->from('auctions')->actingAs($user)->post('auctions', $auction);
    //     $response->assertStatus(302);
    //     $response->assertRedirectToRoute('auctions.index');
    //     $this->assertDatabaseHas('auctions', $auction);
    // }
    // public function test_validation_error_store_auction(): void
    // {
    //     $user = User::factory()->create(['type' => 1]);
    //     $product = Product::factory()->create(['vendor_id'=>$user->id]);
    //     $auction = [
    //         "product_id"=> $product->id,
    //         "lowest_price"=> "10000",
    //         "closing_price"=> "100",
    //         "start_time"=> Carbon::now(),
    //         "closing_time"=> Carbon::now(),
    //     ];
    //     $response = $this->from('auctions/create')->actingAs($user)->post('auctions', $auction);
    //     $response->assertStatus(302);
    //     $response->assertSessionHasErrors(['closing_price' => 'The closing price field must be greater than 10000.']);
    //     $response->assertRedirect('auctions/create');
    // }
    // public function test_get_auction_by_id(): void
    // {
    //     $user = User::factory()->create(['type' => 1]);
    //     $product = Product::factory()->create(['vendor_id'=>$user->id]);
    //     $auction = Auction::factory()->create();
    //     $response = $this->actingAs($user)->get('auctions/' . $auction->id);
    //     $response->assertStatus(200);
    //     $response->assertViewHas('auction', $auction);
    // }
    // public function test_auction_not_found(): void
    // {
    //     $user = User::factory()->create();
    //     $response = $this->actingAs($user)->get('auctions/' . 10000);
    //     $response->assertStatus(404);
    // }
    // public function test_can_access_edit_auction_page_with_existed_auction(): void
    // {
    //     $user = User::factory()->create(['type' => 1]);
    //     Product::factory()->create(['vendor_id'=>$user->id]);
    //     $auction = Auction::factory()->create();
    //     $response = $this->actingAs($user)->get('auctions/' . $auction->id . '/edit');
    //     $response->assertStatus(200);
    //     $response->assertViewHas('auction', $auction);
    // }

    // public function test_cannot_access_edit_auction_page_with_existed_auction(): void
    // {
    //     $user = User::factory()->create(['type' => 2]);
    //     $user2 = User::factory()->create(['type' => 1]);
    //     Product::factory()->create(['vendor_id'=>$user2->id]);
    //     $auction = Auction::factory()->create();
    //     $response = $this->actingAs($user)->get('auctions/' . $auction->id . '/edit');
    //     $response->assertStatus(403);
    // }

    // public function test_can_update_auction(): void
    // {
    //     $user = User::factory()->create(['type' => 1]);
    //     Product::factory()->create(['vendor_id'=>$user->id]);
    //     $auction = Auction::factory()->create();
    //     $fields = ['closing_time'=> Carbon::now()];
    //     $response = $this->from('auctions')
    //     ->actingAs($user)->put('auctions/' . $auction->id,$fields);
    //     $response->assertStatus(302);
    //     $response->assertRedirectToRoute("auctions.index");
    // }
    // public function test_error_update_auction(): void
    // {
    //     $user = User::factory()->create(['type' => 1]);
    //     Product::factory()->create(['vendor_id'=>$user->id]);
    //     $auction = Auction::factory()->create();
    //     $fields = ['start_time'=> Carbon::now()];
    //     $response = $this->from('auctions/'.$auction->id.'/edit')
    //     ->actingAs($user)->put('auctions/' . $auction->id,$fields);
    //     $response->assertStatus(302);
    //     $response->assertSessionHasErrors(['start_time' => 'The start time field must be a date before or equal to closing time.']);
    //     $response->assertRedirect('auctions/'.$auction->id.'/edit');

    // }

    // public function test_can_delete_auction(): void
    // {
    //     $user = User::factory()->create(['type' => 1]);
    //     $product = Product::factory()->create(['vendor_id'=>$user->id]);
    //     $auction = Auction::factory()->create(['product_id'=>$product->id]);
    //     $response = $this->from('auctions')
    //     ->actingAs($user)->delete('auctions/' . $auction->id);
    //     $response->assertStatus(302);
    //     $response->assertRedirectToRoute("auctions.index");
    // }
    // public function test_cannot_delete_auction(): void
    // {
    //     $user = User::factory()->create(['type' => 1]);
    //     $response = $this->from('auctions')
    //     ->actingAs($user)->delete('auctions/' . rand(1,100));
    //     $response->assertStatus(404);
    // }
}
