<?php

namespace App\Services\Interfaces;

interface AuthServiceInterface
{
    /**
     * Attempts to log in with the given credentials.
     *
     * @param array $credentials
     * @return string|null The authentication token if login is successful, or null if it fails.
     */
    public function token(array $credentials): ?string;
}
