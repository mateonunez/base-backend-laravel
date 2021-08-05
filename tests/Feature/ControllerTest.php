<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ControllerTest extends TestCase
{
    /**
     * @group controller
     */
    public function test_index_get_bad_request()
    {
        $response = $this->get('/api/base');

        // Asserting Bad Request
        $response->assertStatus(400);
    }

    /**
     * @group controller
     */
    public function test_show_get_bad_request()
    {
        $response = $this->get('/api/base/fakeId');

        // Asserting Bad Request
        $response->assertStatus(400);
    }

    /**
     * @group controller
     */
    public function test_store_get_bad_request()
    {
        $response = $this->post('/api/base');

        // Asserting Bad Request
        $response->assertStatus(400);
    }
}
