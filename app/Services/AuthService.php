<?php

namespace App\Services;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
class AuthService
{
   
    public function register(array $data, string $deviceName = 'api_token'): array
    {
        return DB::transaction(function () use ($data, $deviceName) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);

            $token = $user->createToken($deviceName)->plainTextToken;

            return [
                'user' => $user,
                'token' => $token,
            ];
        });
    }

    
    public function login(
        array $credentials,
        string $deviceName = 'api_token',
        array $abilities = ['*'],
        bool $revokeExisting = false
    ): array {
        $user = User::where('email', $credentials['email'])->first();

        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        if ($revokeExisting) {
            $user->tokens()->delete();
        }

        $token = $user->createToken($deviceName, $abilities)->plainTextToken;

        return [
            'user' => $user,
            'token' => $token,
        ];
    }

   
    public function logout(Request $request): void
    {
        $token = $request->user()?->currentAccessToken();

        if (! $token) {
            throw new AuthenticationException('No active access token found.');
        }

        // Handle TransientToken when testing or using cookie-based SPA auth
        if (method_exists($token, 'delete')) {
            $token->delete();
        }
    }

    /**
     * Revoke all Sanctum tokens for the authenticated user (e.g., "log out of all devices").
     */
    public function logoutAllDevices(User $user): void
    {
        $user->tokens()->delete();
    }

    
    public function refreshToken(
        Request $request,
        string $deviceName = 'api_token',
        array $abilities = ['*']
    ): array {
        /** @var User|null $user */
        $user = $request->user();

        if (! $user) {
            throw new AuthenticationException('Unauthenticated.');
        }

        $currentToken = $user->currentAccessToken();
        if ($currentToken && method_exists($currentToken, 'delete')) {
            $currentToken->delete();
        }

        $newToken = $user->createToken($deviceName, $abilities)->plainTextToken;

        return [
            'user' => $user,
            'token' => $newToken,
        ];
    }
}
