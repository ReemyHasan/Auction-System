<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommonTests extends TestCase
{
    use RefreshDatabase;
    protected $vendor;
    protected $customer;

    protected function setUp(): void
    {
        parent::setUp();
        $this->vendor = User::factory()->create(['type' => 1]);
        $this->customer = User::factory()->create(['type' => 2]);
    }
    public function index($item, $forwarded): void
    {
        $response = $this->actingAs($this->vendor)->get($forwarded);
        $response->assertStatus(200);
        $response->assertViewHas($forwarded, function ($collection) use ($item) {
            return $collection->contains($item);
        });
    }
    public function index_pagination($items,$forwarded): void
    {
        $last = $items->last();
        $response = $this->actingAs($this->vendor)->get($forwarded);
        $response->assertStatus(200);
        $response->assertViewHas($forwarded, function ($collection) use ($last) {
            return !$collection->contains($last);
        });
    }
    public function vendor_can_access_create($route): void
    {
        $response = $this->actingAs($this->vendor)->get($route);
        $response->assertStatus(200);
    }
    public function customer_cannot_access_create($route): void
    {
        $response = $this->actingAs($this->customer)->get($route);
        $response->assertStatus(403);
    }
    public function store_success($item,$route): void
    {
        $response = $this->from($route)->actingAs($this->vendor)->post($route, $item);
        $response->assertStatus(302);
        $response->assertRedirect($route);
        $this->assertDatabaseHas($route, $item);
    }
    public function validation_error_store($item,$forwarded,$postRoute,$errorMessage): void
    {
        $response = $this->from($forwarded)->actingAs($this->vendor)->post($postRoute, $item);
        $response->assertStatus(302);
        $response->assertSessionHasErrors($errorMessage);
        $response->assertRedirect($forwarded);
    }
    public function get_by_id($item,$route, $view): void
    {
        $response = $this->actingAs($this->vendor)->get($route . $item->id);
        $response->assertStatus(200);
        $response->assertViewHas($view, $item);
    }
    public function not_found($route): void
    {
        $response = $this->actingAs($this->vendor)->get($route . 10000);
        $response->assertStatus(404);
    }
    public function can_access_edit_page_with_existed_item($item,$baseRoute)
    {
        $response = $this->actingAs($this->vendor)->get($baseRoute . $item->id . '/edit');
        $response->assertStatus(200);
        return $response;
    }

    public function cannot_access_edit_page_with_unauthorized_user($route): void
    {
        $response = $this->actingAs($this->customer)->get($route);
        $response->assertStatus(403);
    }
}
