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
    protected $vendor;
    protected $customer;
    protected $category;

    protected function setUp(): void
    {
        parent::setUp();
        $this->vendor = User::factory()->create(['type' => 1]);
        $this->customer = User::factory()->create(['type' => 2]);
        $this->category = Category::factory()->create(['created_by' => $this->vendor]);
    }
    public function test_index(): void
    {
        $category = $this->category;
        $response = $this->actingAs($this->vendor)->get('/categories');
        $response->assertStatus(200);
        $response->assertViewHas('categories', function ($collection) use ($category) {
            return $collection->contains($category);
        });
    }
    public function test_index_pagination(): void
    {
        $categories = Category::factory(11)->create(['created_by' => $this->vendor]);
        $last = $categories->last();
        $response = $this->actingAs($this->vendor)->get('/categories');
        $response->assertStatus(200);
        $response->assertViewHas('categories', function ($collection) use ($last) {
            return !$collection->contains($last);
        });
    }

    public function test_vendor_can_access_create_category(): void
    {
        $response = $this->actingAs($this->vendor)->get('/categories/create');
        $response->assertStatus(200);
    }
    public function test_customer_cannot_access_create_category(): void
    {
        $response = $this->actingAs($this->customer)->get('/categories/create');
        $response->assertStatus(403);
    }
    public function test_store_category(): void
    {
        $category = [
            'name' => 'category',
            'description' => 'category description',
            'created_by' => $this->vendor->id
        ];
        $response = $this->from('categories')->actingAs($this->vendor)->post('categories', $category);
        $response->assertStatus(302);
        $response->assertRedirectToRoute('categories.index');
        $this->assertDatabaseHas('categories', $category);
    }
    public function test_validation_error_store_category(): void
    {
        $category = [
            'name' => 'l',
            'description' => 'category description',
            'created_by' => $this->vendor
        ];
        $response = $this->from('categories/create')->actingAs($this->vendor)->post('categories', $category);
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['name' => 'The name field must be at least 5 characters.']);
        $response->assertRedirect('categories/create');
    }
    public function test_get_category_by_id(): void
    {
        $response = $this->actingAs($this->vendor)->get('categories/' . $this->category->id);
        $response->assertStatus(200);
        $response->assertViewHas('category', $this->category);
    }
    public function test_category_not_found(): void
    {
        $response = $this->actingAs($this->vendor)->get('categories/' . 10000);
        $response->assertStatus(404);
    }
    public function test_can_access_edit_category_page_with_existed_category(): void
    {
        $response = $this->actingAs($this->vendor)->get('categories/' . $this->category->id . '/edit');
        $response->assertStatus(200);
        $response->assertViewHas('category', $this->category);
        $response->assertSee('value="' . $this->category->name . '"', false);
    }

    public function test_cannot_access_edit_category_page_with_existed_category(): void
    {
        $response = $this->actingAs($this->customer)->get('categories/' . $this->category->id . '/edit');
        $response->assertStatus(403);
    }

    public function test_can_update_category(): void
    {
        $fields = ['name' => 'category100'];
        $response = $this->from('categories')
            ->actingAs($this->vendor)->put('categories/' . $this->category->id, $fields);
        $response->assertStatus(302);
        $response->assertRedirectToRoute("categories.index");
    }
    public function test_error_update_category(): void
    {
        $fields = ['name' => 'pr'];
        $response = $this->from('categories/' . $this->category->id . '/edit')
            ->actingAs($this->vendor)->put('categories/' . $this->category->id, $fields);
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['name' => 'The name field must be at least 5 characters.']);
        $response->assertRedirect('categories/' . $this->category->id . '/edit');

    }

    public function test_can_delete_category(): void
    {
        $response = $this->from('categories')
            ->actingAs($this->vendor)->delete('categories/' . $this->category->id);
        $response->assertStatus(302);
        $response->assertRedirectToRoute("categories.index");
    }
    public function test_cannot_delete_category(): void
    {
        $response = $this->from('categories')
            ->actingAs($this->vendor)->delete('categories/' . rand(1, 100));
        $response->assertStatus(404);
    }
}
