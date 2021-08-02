<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ControllerTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_indexing_get_bad_request()
    {
        $response = $this->get('/api');

        $response->assertStatus(400);
    }
}
