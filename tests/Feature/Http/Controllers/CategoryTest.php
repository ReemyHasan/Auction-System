<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;
    public function test_index(): void
    {
        $users = User::factory(5)->create();
        $category = Category::factory()->create();
        $user = $users->last();
        $response = $this->actingAs($user)->get('/categories');
        $response->assertStatus(200);
        $response->assertViewHas('categories',function($collection) use ($category){
            return $collection->contains($category);
        });
    }
    public function test_index_pagination(): void
    {
        $users = User::factory(11)->create();
        $categories = Category::factory(11)->create();
        $last = $categories->last();
        $user = $users->last();
        $response = $this->actingAs($user)->get('/categories');
        $response->assertStatus(200);
        $response->assertViewHas('categories',function($collection) use ($last){
            return !$collection->contains($last);
        });
    }

        public function test_vendor_can_access_create_category(): void
    {
        $user = User::factory()->create(['type' => 1]); //1 for vendor
        $response = $this->actingAs($user)->get('/categories/create');
        $response->assertStatus(200);
    }
    public function test_customer_cannot_access_create_category(): void
    {
        $user = User::factory()->create(['type'=>'2']); //2 for customer
        $response = $this->actingAs($user)->get('/categories/create');
        $response->assertStatus(403);
    }
    public function test_store_category(): void
    {
        $user = User::factory()->create(['type' => 1]);
        $category = [
            'name' => 'category',
            'description' => 'category description',
            'created_by' => $user->id
        ];
        $response = $this->from('categories')->actingAs($user)->post('categories', $category);
        $response->assertStatus(302);
        $response->assertRedirectToRoute('categories.index');
        $this->assertDatabaseHas('categories', $category);
    }
    public function test_validation_error_store_category(): void
    {
        $user = User::factory()->create(['type' => 1]);
        $category = [
            'name' => 'l',
            'description' => 'category description',
            'created_by' => $user->id
        ];
        $response = $this->from('categories/create')->actingAs($user)->post('categories', $category);
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['name' => 'The name field must be at least 5 characters.']);
        $response->assertRedirect('categories/create');
    }
    public function test_get_category_by_id(): void
    {
        $user = User::factory()->create(['type' => 1]);
        $category = Category::factory()->create(['created_by' => $user->id]);
        $response = $this->actingAs($user)->get('categories/' . $category->id);
        $response->assertStatus(200);
        $response->assertViewHas('category', $category);
    }
    public function test_category_not_found(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('categories/' . 10000);
        $response->assertStatus(404);
    }
    public function test_can_access_edit_category_page_with_existed_category(): void
    {
        $user = User::factory()->create(['type' => 1]);
        $category = Category::factory()->create(['created_by' => $user->id]);
        $response = $this->actingAs($user)->get('categories/' . $category->id . '/edit');
        $response->assertStatus(200);
        $response->assertViewHas('category', $category);
        $response->assertSee('value="'.$category->name.'"',false);
    }

    public function test_cannot_access_edit_category_page_with_existed_category(): void
    {
        $user = User::factory()->create(['type' => '2']);
        $category = Category::factory()->create(['created_by' => $user->id]);
        $response = $this->actingAs($user)->get('categories/' . $category->id.'/edit');
        $response->assertStatus(403);
    }

    public function test_can_update_category(): void
    {
        $user = User::factory()->create(['type' => 1]);
        $category = Category::factory()->create(['created_by' => $user->id]);
        $fields = ['name'=>'category100'];
        $response = $this->from('categories')
        ->actingAs($user)->put('categories/' . $category->id,$fields);
        $response->assertStatus(302);
        $response->assertRedirectToRoute("categories.index");
    }
    public function test_error_update_category(): void
    {
        $user = User::factory()->create(['type' => 1]);
        $category = Category::factory()->create(['created_by' => $user->id]);
        $fields = ['name'=>'pr'];
        $response = $this->from('categories/'.$category->id.'/edit')
        ->actingAs($user)->put('categories/' . $category->id,$fields);
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['name' => 'The name field must be at least 5 characters.']);
        $response->assertRedirect('categories/'.$category->id.'/edit');

    }

    public function test_can_delete_category(): void
    {
        $user = User::factory()->create(['type' => 1]);
        $category = Category::factory()->create(['created_by' => $user->id]);
        $response = $this->from('categories')
        ->actingAs($user)->delete('categories/' . $category->id);
        $response->assertStatus(302);
        $response->assertRedirectToRoute("categories.index");
    }
    public function test_cannot_delete_category(): void
    {
        $user = User::factory()->create(['type' => 1]);
        $response = $this->from('categories')
        ->actingAs($user)->delete('categories/' . rand(1,100));
        $response->assertStatus(404);
    }
}
