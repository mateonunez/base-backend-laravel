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

    /**
     * @group controller
     */
    public function test_update_get_bad_request()
    {
        $response = $this->put('/api/base/fakeId');

        // Asserting Bad Request
        $response->assertStatus(400);


        $response = $this->patch('/api/base/fakeId');

        // Asserting Bad Request
        $response->assertStatus(400);

        // Asserting Bad Request
        $response->assertStatus(400);
    }

    /**
     * @group controller
     */
    public function test_destroy_get_bad_request()
    {
        $response = $this->delete('/api/base/fakeId');

        // Asserting Bad Request
        $response->assertStatus(400);
    }
}
