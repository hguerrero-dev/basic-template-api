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
}
