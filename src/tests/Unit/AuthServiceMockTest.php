<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\AuthService;
use Mockery;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthServiceMockTest extends TestCase
{
    protected $authService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->authService = new AuthService();
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_login_with_valid_credentials_returns_token()
    {
        $userMock = Mockery::mock(User::class);
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

        $token = $this->authService->token([
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $this->assertEquals('mocked_token', $token);
    }

    public function test_login_with_invalid_credentials_returns_null()
    {
        Auth::shouldReceive('attempt')
            ->once()
            ->with(['email' => 'test@example.com', 'password' => 'wrongpassword'])
            ->andReturn(false);

        $token = $this->authService->token([
            'email' => 'test@example.com',
            'password' => 'wrongpassword',
        ]);

        $this->assertNull($token);
    }
}
