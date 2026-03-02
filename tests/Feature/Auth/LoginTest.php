<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Modules\Users\Models\User;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_with_valid_credentials_returns_token()
    {
        // => 1. Create a user
        User::factory()->create([
            'username' => 'testuser',
            'password' => bcrypt('password123'),
        ]);

        // => 2. Attempt to login
        $response = $this->postJson('/api/v1/auth/login', [
            'username' => 'testuser',
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => ['token']
            ]);
    }

    public function test_login_rate_limiting()
    {
        // Create a user for login attempts
        User::factory()->create([
            'username' => 'ratelimituser',
            'password' => bcrypt('password123'),
        ]);

        // Make 5 login attempts (the limit)
        for ($i = 0; $i < 5; $i++) {
            $response = $this->postJson('/api/v1/auth/login', [
                'username' => 'ratelimituser',
                'password' => 'wrongpassword', // Use wrong password to avoid successful login
            ]);
            $response->assertStatus(401); // Expecting unauthorized for wrong credentials
        }

        // Make one more attempt, which should be rate-limited
        $response = $this->postJson('/api/v1/auth/login', [
            'username' => 'ratelimituser',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(429); // Expecting Too Many Requests
    }
}
