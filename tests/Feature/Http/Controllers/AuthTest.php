<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;
    public function test_login(): void
    {
        $user = User::factory()->create();
        $response = $this->post('login',[
            'email' => $user->email,
            'password'=> $user->password
        ]);
        $response->assertStatus(302);
        $response->assertRedirectToRoute('home');
    }
    public function test_cannot_login(): void
    {
        $user = User::factory()->create();
        $response = $this->from('login')->post('login',[
            'email' => $user->email,
            'password'=> 'd'
        ]);
        $response->assertStatus(302);
        $response->assertRedirectToRoute('login');
    }
    public function test_register(): void
    {
        $response = $this->post('register',[
            "email"=> 'example@gmail.com',
            "name" => "example",
            "password"=> "123qwe",
            "password_confirmation"=>"123qwe",
            "type"=>"1"
        ]);
        $response->assertStatus(302);
        $response->assertRedirectToRoute('home');
    }
    public function test_register_validation_error(): void
    {
        $response = $this->from('register')->post('register',[
            "email"=> 'gmail.com',
            "name" => "example",
            "password"=> "123qwe",
            "password_confirmation"=>"123",
            "type"=>"1"
        ]);
        $response->assertStatus(302);
        $response->assertRedirectToRoute('register');
        $response->assertSessionHasErrors('password','The password field confirmation does not match.');
        $response->assertSessionHasErrors('email','The email field must be a valid email address.');
    }

    public function test_logout(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/logout');
        $response->assertStatus(302);
        $response->assertRedirectToRoute('login');
    }
    public function test_unauthenticated_logout(): void
    {
        $response = $this->get('/logout');
        $response->assertStatus(302);
        $response->assertRedirectToRoute('login');
    }
    public function test_unautheticated_user_cannot_access_pages_need_authentication(): void
    {
        $response = $this->get('/products');

        $response->assertStatus(302);
        $response->assertRedirect('login');
    }
}
