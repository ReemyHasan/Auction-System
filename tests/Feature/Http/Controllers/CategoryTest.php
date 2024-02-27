<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Category;

class CategoryTest extends CommonTests
{
    protected $category;

    protected function setUp(): void
    {
        parent::setUp();
        $this->category = Category::factory()->create(['created_by' => $this->vendor]);
    }
    public function test_index(): void
    {
        $category = $this->category;
        $this->index($category,'categories');
    }
    public function test_index_pagination(): void
    {
        $categories = Category::factory(11)->create(['created_by' => $this->vendor]);
        $this->index_pagination($categories,'categories');
    }

    public function test_vendor_can_access_create_category(): void
    {
        $this->vendor_can_access_create('/categories/create');
    }
    public function test_customer_cannot_access_create_category(): void
    {
        $this->customer_cannot_access_create('/categories/create');

    }
    public function test_store_category(): void
    {
        $category = [
            'name' => 'category',
            'description' => 'category description',
            'created_by' => $this->vendor->id
        ];
        $this->store_success($category,'categories');
    }
    public function test_validation_error_store_category(): void
    {
        $category = [
            'name' => 'l',
            'description' => 'category description',
            'created_by' => $this->vendor
        ];
        $errorMessage = ['name' => 'The name field must be at least 5 characters.'];
        $this->validation_error_store($category,'categories/create','categories',$errorMessage);
    }
    public function test_get_category_by_id(): void
    {
        $this->get_by_id($this->category,'categories/','category');
    }
    public function test_category_not_found(): void
    {
        $this->not_found('categories/');
    }
    public function test_can_access_edit_category_page_with_existed_category(): void
    {
        $response = $this->can_access_edit_page_with_existed_item($this->category,'categories/');
        $response->assertViewHas('category', $this->category);
        $response->assertSee('value="' . $this->category->name . '"', false);
    }

    public function test_cannot_access_edit_category_page_with_existed_category(): void
    {
        $this->cannot_access_edit_page_with_unauthorized_user('categories/' . $this->category->id . '/edit');
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
