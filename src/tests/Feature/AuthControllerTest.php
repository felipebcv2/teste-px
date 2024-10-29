<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class AuthControllerTest extends TestCase
{
    use WithoutMiddleware;

    public function test_token_with_valid_credentials_returns_token()
    {
        $userMock = \Mockery::mock(User::class);
        $userMock->shouldReceive('createToken')
            ->once()
            ->with('token')
            ->andReturn((object) ['plainTextToken' => 'mocked_token']);

        Auth::shouldReceive('attempt')
            ->once()
            ->with(['email' => 'test@example.com', 'password' => 'password123'])
            ->andReturn(true);

        Auth::shouldReceive('user')
            ->once()
            ->andReturn($userMock);

        Auth::shouldReceive('userResolver')
            ->andReturnUsing(fn() => $userMock);

        $response = $this->postJson('/api/v1/token', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['token'])
            ->assertJson([
                'token' => 'Bearer mocked_token',
            ]);
    }

    public function test_token_with_invalid_credentials_returns_unauthorized()
    {
        Auth::shouldReceive('attempt')
            ->once()
            ->with(['email' => 'test@example.com', 'password' => 'wrongpassword'])
            ->andReturn(false);

        Auth::shouldReceive('userResolver')
            ->andReturn(null);

        $response = $this->postJson('/api/v1/token', [
            'email' => 'test@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(401)
            ->assertJson(['message' => 'Invalid credentials.']);
    }
}
