<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        Artisan::call('migrate', ['-vvv' => true]);
        Artisan::call('passport:install', ['-vvv' => true]);
        Artisan::call('db:seed', ['-vvv' => true]);
    }

    /**
     * @group auth_controller
     */
    public function test_register_validation_fails()
    {
        $payload = [
            'name' => 'Test',
            'password' => bcrypt('Test')
        ];

        $response = $this->post('/api/auth/register', $payload);

        $response->assertStatus(400);
        $response->assertSeeText('validation.required');
    }

    /**
     * @group auth_controller
     */
    public function test_register()
    {
        $payload = [
            'name' => 'Test',
            'email' => 'test@example.com',
            'password' => 'Test'
        ];

        $response = $this->post('/api/auth/register', $payload);

        $response->assertStatus(201);
        $response->assertSeeText('token');
    }

    /**
     * @group auth_controller
     */
    public function test_login_validation_fails()
    {
        $payload = [
            'email' => 'test@example.com',
        ];

        $response = $this->post('/api/auth/login', $payload);

        $response->assertStatus(400);
        $response->assertSeeText('validation.required');
    }

    /**
     * @group auth_controller
     */
    public function test_login()
    {
        $user = \App\Models\User::factory()->create();

        $payload = [
            'email' => $user->email,
            'password' => 'password'
        ];

        $response = $this->post('/api/auth/login', $payload);

        $response->assertStatus(200);
        $response->assertSeeText('token');
    }
}
