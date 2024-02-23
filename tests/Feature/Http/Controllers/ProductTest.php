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

    private function creatUsers()
    {
        return User::factory(10)->create();
    }
    public function test_index(): void
    {
        $users = $this->creatUsers();
        $product = Product::factory()->create();
        $user = $users->last();
        $response = $this->actingAs($user)->get('/products');
        $response->assertStatus(200);
        $response->assertViewHas('products', function ($collection) use ($product) {
            return $collection->contains($product);
        });
    }
    public function test_index_pagination(): void
    {
        $users = $this->creatUsers();
        $products = Product::factory(11)->create();
        $last = $products->last();
        $user = $users->last();
        $response = $this->actingAs($user)->get('/products');
        $response->assertStatus(200);
        $response->assertViewHas('products', function ($collection) use ($last) {
            return !$collection->contains($last);
        });
    }
    public function test_vendor_can_access_create_product(): void
    {
        $user = User::factory()->create(['type' => '1']); //1 for vendor
        $response = $this->actingAs($user)->get('/products/create');
        $response->assertStatus(200);
    }
    public function test_customer_cannot_access_create_product(): void
    {
        $user = User::factory()->create(['type'=>'2']); //2 for customer
        $response = $this->actingAs($user)->get('/products/create');
        $response->assertStatus(403);
    }
    public function test_store_product(): void
    {
        $user = User::factory()->create(['type' => '1']);
        $productFactory = Product::factory()->create();
        $product = [
            'name' => $productFactory->name,
            'description' => $productFactory->description,
            'status' => $productFactory->status,
            'count' => $productFactory->count,
            'image' => $productFactory->image,
            'vendor_id' => $user->id
        ];
        $response = $this->from('products')->actingAs($user)->post('products', $product);
        $response->assertStatus(302);
        $response->assertRedirectToRoute('products.index');
        $this->assertDatabaseHas('products', $product);
    }
    public function test_validation_error_store_product(): void
    {
        $user = User::factory()->create(['type' => '1']);
        $productFactory = Product::factory()->create(['name' => '3']);
        $product = [
            'name' => $productFactory->name,
            'description' => $productFactory->description,
            'status' => $productFactory->status,
            'count' => $productFactory->count,
            'image' => $productFactory->image,
            'vendor_id' => $user->id
        ];
        $response = $this->from('products/create')->actingAs($user)->post('products', $product);
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['name' => 'The name field must be at least 5 characters.']);
        $response->assertRedirect('products/create');
    }
    public function test_get_product_by_id(): void
    {
        $user = User::factory()->create(['type' => '1']);
        $product = Product::factory()->create(['vendor_id' => $user->id]);
        $response = $this->actingAs($user)->get('products/' . $product->id);
        $response->assertStatus(200);
        $response->assertViewHas('product', $product);
    }
    public function test_product_not_found(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('products/' . 100);
        $response->assertStatus(404);
    }
    public function test_can_access_edit_product_page_with_existed_product(): void
    {
        $user = User::factory()->create(['type' => 1]);
        $product = Product::factory()->create(['vendor_id' => $user->id]);
        $categories = Category::factory(3)->create();
        $product->categories()->attach(
            $categories->random(1)
        );
        $checked = $product->categories()->pluck('categories.id')->toArray();
        $this->assertEquals($user->id, $product->vendor_id);
        $response = $this->actingAs($user)->get('products/' . $product->id . '/edit');
        $response->assertStatus(200);
        $response->assertViewHas('product', $product);
        $response->assertViewHas('categories', $categories);
        $response->assertViewHas('checked', $checked);
        $response->assertSee('value="'.$product->name.'"',false);
    }

    public function test_cannot_access_edit_product_page_with_existed_product(): void
    {
        $user = User::factory()->create(['type' => '2']);
        $product = Product::factory()->create(['vendor_id' => $user->id]);
        $response = $this->actingAs($user)->get('products/' . $product->id.'/edit');
        $response->assertStatus(403);
    }

    public function test_can_update_product(): void
    {
        $user = User::factory()->create(['type' => 1]);
        $product = Product::factory()->create(['vendor_id' => $user->id]);
        $categories = Category::factory(3)->create();
        $product->categories()->attach(
            $categories->random(rand(1,3))
        );
        $updatedCategories = $categories->random(rand(1,3))->pluck('id')->toArray();
        $fields = ['name'=>'product1', 'category_id[]'=>$updatedCategories];
        $response = $this->from('products')
        ->actingAs($user)->put('products/' . $product->id,$fields);
        $response->assertStatus(302);
        $response->assertRedirectToRoute("products.index");
    }
    public function test_error_update_product(): void
    {
        $user = User::factory()->create(['type' => 1]);
        $product = Product::factory()->create(['vendor_id' => $user->id]);
        $categories = Category::factory(3)->create();
        $product->categories()->attach(
            $categories->random(rand(1,3))
        );
        $updatedCategories = $categories->random(rand(1,3))->pluck('id')->toArray();
        $fields = ['image'=>'pro1', 'category_id[]'=>$updatedCategories];
        $response = $this->from('products/'.$product->id.'/edit')
        ->actingAs($user)->put('products/' . $product->id,$fields);
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['image' => 'The image field must be an image.']);
        $response->assertRedirect('products/'.$product->id.'/edit');

    }

    public function test_can_delete_product(): void
    {
        $user = User::factory()->create(['type' => 1]);
        $product = Product::factory()->create(['vendor_id' => $user->id]);
        $categories = Category::factory(3)->create();
        $product->categories()->attach(
            $categories->random(rand(1,3))
        );
        $response = $this->from('products')
        ->actingAs($user)->delete('products/' . $product->id);
        $response->assertStatus(302);
        $response->assertRedirectToRoute("products.index");
    }
    public function test_cannot_delete_product(): void
    {
        $user = User::factory()->create(['type' => 1]);
        $response = $this->from('products')
        ->actingAs($user)->delete('products/' . rand(1,100));
        $response->assertStatus(404);
    }
}
