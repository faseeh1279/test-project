<?php

namespace App\Services;

use App\Models\User; 

class JwtTokenService
{
    /**
     * Generate a short-lived access token.
     * Includes roles in the claims so you can check them without a DB hit.
     */
    public function GenerateAccessToken(User $user, array $customClaims = []): string
    {
        $claims = array_merge([
            'token_type' => 'access',
            'roles' => method_exists($user, 'getRoleNames')
                ? $user->getRoleNames()->toArray()
                : [],
        ], $customClaims);

        // ttl comes from config/jwt.php, default 60 minutes
        return auth('api')
            ->setTTL(config('jwt.ttl', 60))
            ->claims($claims)
            ->fromUser($user);
    }

    /**
     * Generate a long-lived refresh token.
     * Keep this minimal, no roles/permissions in here.
     */
    public function GenerateRefreshToken(User $user): string
    {
        $claims = [
            'token_type' => 'refresh',
        ];

        // refresh_ttl comes from config/jwt.php, default 20160 minutes = 14 days
        return auth('api')
            ->setTTL(config('jwt.refresh_ttl', 20160))
            ->claims($claims)
            ->fromUser($user);
    }
}
