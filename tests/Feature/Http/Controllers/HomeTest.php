<?php

namespace Tests\Feature\Http\Controllers;

use Tests\TestCase;

class HomeTest extends TestCase
{
    public function test_home(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
