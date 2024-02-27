<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    protected $vendor;
    protected $customer;
    protected $product;
    protected $categories;

    // protected function setUp(): void
    // {
    //     parent::setUp();
    //     $this->vendor = User::factory()->create(['type' => 1]);
    //     $this->customer = User::factory()->create(['type' => 2]);
    //     $this->product = Product::factory()->create(['vendor_id' => $this->vendor->id]);
    //     $this->categories = Category::factory(3)->create();
    //     $this->product->categories()->attach(
    //         $this->categories->random(1)
    //     );
    // }
    // public function test_index(): void
    // {
    //     $product = $this->product;
    //     $response = $this->actingAs($this->vendor)->get('/products');
    //     $response->assertStatus(200);
    //     $response->assertViewHas('products', function ($collection) use ($product) {
    //         return $collection->contains($product);
    //     });
    // }
    // public function test_index_pagination(): void
    // {
    //     $products = Product::factory(11)->create(['vendor_id' => $this->vendor->id]);
    //     $last = $products->last();
    //     $response = $this->actingAs($this->vendor)->get('/products');
    //     $response->assertStatus(200);
    //     $response->assertViewHas('products', function ($collection) use ($last) {
    //         return !$collection->contains($last);
    //     });
    // }
    // public function test_vendor_can_access_create_product(): void
    // {
    //     $response = $this->actingAs($this->vendor)->get('/products/create');
    //     $response->assertStatus(200);
    // }
    // public function test_customer_cannot_access_create_product(): void
    // {
    //     $response = $this->actingAs($this->customer)->get('/products/create');
    //     $response->assertStatus(403);
    // }
    // public function test_store_product(): void
    // {
    //     $product = [
    //         'name' => 'productname',
    //         'description' => 'product description',
    //         'status' => '0',
    //         'count' => '10',
    //         'vendor_id' => $this->vendor->id
    //     ];
    //     $response = $this->from('products')->actingAs($this->vendor)->post('products', $product);
    //     $response->assertStatus(302);
    //     $response->assertRedirectToRoute('products.index');
    //     $this->assertDatabaseHas('products', $product);
    // }
    // public function test_validation_error_store_product(): void
    // {
    //     $product = [
    //         'name' => '3',
    //         'description' => 'product description',
    //         'status' => '0',
    //         'count' => '10',
    //         'vendor_id' => $this->vendor->id
    //     ];
    //     $response = $this->from('products/create')->actingAs($this->vendor)->post('products', $product);
    //     $response->assertStatus(302);
    //     $response->assertSessionHasErrors(['name' => 'The name field must be at least 5 characters.']);
    //     $response->assertRedirect('products/create');
    // }
    // public function test_get_product_by_id(): void
    // {
    //     $response = $this->actingAs($this->vendor)->get('products/' . $this->product->id);
    //     $response->assertStatus(200);
    //     $response->assertViewHas('product', $this->product);
    // }
    // public function test_product_not_found(): void
    // {
    //     $response = $this->actingAs($this->vendor)->get('products/' . 100);
    //     $response->assertStatus(404);
    // }
    // public function test_can_access_edit_product_page_with_existed_product(): void
    // {

    //     $checked = $this->product->categories()->pluck('categories.id')->toArray();
    //     $response = $this->actingAs($this->vendor)->get('products/' . $this->product->id . '/edit');
    //     $response->assertStatus(200);
    //     $response->assertViewHas('product', $this->product);
    //     $response->assertViewHas('categories', $this->categories);
    //     $response->assertViewHas('checked', $checked);
    //     $response->assertSee('value="'.$this->product->name.'"',false);
    // }

    // public function test_cannot_access_edit_product_page_with_unauthorized_user(): void
    // {
    //     $product = Product::factory()->create(['vendor_id' => $this->customer->id]);
    //     $response = $this->actingAs($this->customer)->get('products/' . $product->id.'/edit');
    //     $response->assertStatus(403);
    // }

    // public function test_can_update_product(): void
    // {
    //     $updatedCategories = $this->categories->random(rand(1,3))->pluck('id')->toArray();
    //     $fields = ['name'=>'product1', 'category_id[]'=>$updatedCategories];
    //     $response = $this->from('products')
    //     ->actingAs($this->vendor)->put('products/' . $this->product->id,$fields);
    //     $response->assertStatus(302);
    //     $response->assertRedirectToRoute("products.index");
    // }
    // public function test_error_update_product(): void
    // {
    //     $updatedCategories = $this->categories->random(rand(1,3))->pluck('id')->toArray();
    //     $fields = ['image'=>'pro1', 'category_id[]'=>$updatedCategories];
    //     $response = $this->from('products/'.$this->product->id.'/edit')
    //     ->actingAs($this->vendor)->put('products/' . $this->product->id,$fields);
    //     $response->assertStatus(302);
    //     $response->assertSessionHasErrors(['image' => 'The image field must be an image.']);
    //     $response->assertRedirect('products/'.$this->product->id.'/edit');

    // }

    // public function test_can_delete_product(): void
    // {
    //     $product = Product::factory()->create(['vendor_id' => $this->vendor->id]);
    //     $product->categories()->attach(
    //         $this->categories->random(rand(1,3))
    //     );
    //     $response = $this->from('products')
    //     ->actingAs($this->vendor)->delete('products/' . $product->id);
    //     $response->assertStatus(302);
    //     $response->assertRedirectToRoute("products.index");
    // }
    // public function test_cannot_delete_product(): void
    // {
    //     $response = $this->from('products')
    //     ->actingAs($this->vendor)->delete('products/' . rand(1,100));
    //     $response->assertStatus(404);
    // }

    // public function test_can_access_to_add_interaction_to_product_view()
    // {
    //     $response = $this->actingAs($this->customer)->get('products/'.$this->product->id.'/add_interaction');
    //     $response->assertStatus(200);

    // }
    // public function test_cannot_access_add_interaction_to_product_unauthorized_access()
    // {
    //     $vendor = User::factory()->create(['type' => 1]);
    //     $response = $this->actingAs($vendor)->get('products/'.$this->product->id.'/add_interaction');
    //     $response->assertStatus(403);

    // }
    // public function test_cannot_access_add_multiple_interaction_to_product()
    // {
    //     $this->product->interactions()->create([
    //         'rate' => 4,
    //         'comment' => 'comment',
    //         'user_id' =>$this->customer->id,
    //     ]);
    //     $response = $this->actingAs($this->customer)->get('products/'.$this->product->id.'/add_interaction');
    //     $response->assertStatus(403);

    // }
    // public function test_can_store_interaction_to_product()
    // {
    //     $response = $this->from('products')
    //     ->actingAs($this->customer)->post('products/'.$this->product->id.'/store_interaction',
    //     [
    //         'rate' => 4,
    //         'comment' => 'comment'
    //     ]);
    //     $response->assertStatus(302);
    //     $response->assertRedirectToRoute('products.index');
    //     $response->assertSessionHasNoErrors();

    // }
    // public function test_cannot_store_interaction_to_product(){
    //     $vendor = User::factory()->create(['type' => 1]);
    //     $response = $this->from('products')
    //     ->actingAs($vendor)->post('products/'.$this->product->id.'/store_interaction',
    //     [
    //         'rate' => 4,
    //         'comment' => 'comment'
    //     ]);
    //     $response->assertStatus(403);

    // }
}
