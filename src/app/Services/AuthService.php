<?php

namespace App\Services;



use App\Services\Interfaces\AuthServiceInterface;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthService implements AuthServiceInterface
{
    public function token(array $credentials): ?string
    {
        if (!Auth::attempt($credentials)) {
            return null;
        }

        $user = Auth::user();
        return $user->createToken('token')->plainTextToken;
    }
}
