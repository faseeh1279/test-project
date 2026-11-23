<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\JwtTokenService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use OpenApi\Attributes as OA;

class AuthController extends Controller
{
    public function __construct(
        protected JwtTokenService $jwtTokenService
    ) {}

    #[OA\Post(
        path: '/api/register',
        operationId: 'registerUser',
        summary: 'Register a new user',
        description: 'Creates a new user account, assigns a default role, and returns access and refresh tokens.',
        tags: ['Auth'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['name', 'email', 'password', 'password_confirmation'],
                properties: [
                    new OA\Property(property: 'name', type: 'string', example: 'admin2'),
                    new OA\Property(property: 'email', type: 'string', format: 'email', example: 'admin2@admin2.com'),
                    new OA\Property(property: 'password', type: 'string', format: 'password', example: 'admin123'),
                    new OA\Property(property: 'password_confirmation', type: 'string', format: 'password', example: 'admin123'),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Registration successful',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Registered successfully.'),
                        new OA\Property(property: 'access_token', type: 'string', example: 'eyJ0eXAiOiJKV1Q...'),
                        new OA\Property(property: 'refresh_token', type: 'string', example: 'eyJ0eXAiOiJKV1Q...'),
                        new OA\Property(property: 'token_type', type: 'string', example: 'bearer'),
                        new OA\Property(property: 'expires_in', type: 'integer', example: 3600),
                        new OA\Property(
                            property: 'user',
                            type: 'object',
                            properties: [
                                new OA\Property(property: 'id', type: 'integer', example: 1),
                                new OA\Property(property: 'name', type: 'string', example: 'John Doe'),
                                new OA\Property(property: 'email', type: 'string', example: 'john@example.com'),
                                new OA\Property(property: 'roles', type: 'array', items: new OA\Items(type: 'string'), example: ['user']),
                                new OA\Property(property: 'permissions', type: 'array', items: new OA\Items(type: 'string'), example: []),
                            ]
                        ),
                    ]
                )
            ),
            new OA\Response(response: 422, description: 'Validation error'),
        ]
    )]
    public function register(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        // Ensure 'user' role exists for the 'api' guard before assigning
        if (method_exists($user, 'assignRole')) {
            $user->assignRole('user');
        }

        $accessToken = $this->jwtTokenService->GenerateAccessToken($user);
        $refreshToken = $this->jwtTokenService->GenerateRefreshToken($user);

        return response()->json([
            'message' => 'Registered successfully.',
            'access_token' => $accessToken,
            'refresh_token' => $refreshToken,
            'token_type' => 'bearer',
            'expires_in' => (int) config('jwt.ttl', 60) * 60,
            'user' => $this->formatUser($user),
        ], 201);
    }

    #[OA\Post(
        path: '/api/login',
        operationId: 'loginUser',
        summary: 'Login with email and password',
        description: 'Authenticates a user with email and password and returns an access token and a refresh token.',
        tags: ['Auth'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['email', 'password'],
                properties: [
                    new OA\Property(
                        property: 'email',
                        type: 'string',
                        format: 'email',
                        example: 'admin2@admin2.com'
                    ),
                    new OA\Property(
                        property: 'password',
                        type: 'string',
                        format: 'password',
                        example: 'admin123'
                    ),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Login successful',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'access_token', type: 'string', example: 'eyJ0eXAiOiJKV1Q...'),
                        new OA\Property(property: 'refresh_token', type: 'string', example: 'eyJ0eXAiOiJKV1Q...'),
                        new OA\Property(property: 'token_type', type: 'string', example: 'bearer'),
                        new OA\Property(property: 'expires_in', type: 'integer', example: 3600),
                        new OA\Property(
                            property: 'user',
                            type: 'object',
                            properties: [
                                new OA\Property(property: 'id', type: 'integer', example: 1),
                                new OA\Property(property: 'name', type: 'string', example: 'John Doe'),
                                new OA\Property(property: 'email', type: 'string', example: 'john@example.com'),
                                new OA\Property(
                                    property: 'roles',
                                    type: 'array',
                                    items: new OA\Items(type: 'string'),
                                    example: ['admin']
                                ),
                                new OA\Property(
                                    property: 'permissions',
                                    type: 'array',
                                    items: new OA\Items(type: 'string'),
                                    example: ['edit posts']
                                ),
                            ]
                        ),
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: 'Invalid credentials',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Invalid credentials'),
                    ]
                )
            ),
            new OA\Response(
                response: 422,
                description: 'Validation error',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'The given data was invalid.'),
                        new OA\Property(
                            property: 'errors',
                            type: 'object',
                            properties: [
                                new OA\Property(
                                    property: 'email',
                                    type: 'array',
                                    items: new OA\Items(type: 'string'),
                                    example: ['The email field is required.']
                                ),
                                new OA\Property(
                                    property: 'password',
                                    type: 'array',
                                    items: new OA\Items(type: 'string'),
                                    example: ['The password field is required.']
                                ),
                            ]
                        ),
                    ]
                )
            ),
        ]
    )]
    public function login(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('email', $validated['email'])->first();

        if (! $user || ! Hash::check($validated['password'], $user->password)) {
            return response()->json([
                'message' => 'Invalid credentials',
            ], 401);
        }

        $accessToken = $this->jwtTokenService->GenerateAccessToken($user);
        $refreshToken = $this->jwtTokenService->GenerateRefreshToken($user);

        return response()->json([
            'access_token' => $accessToken,
            'refresh_token' => $refreshToken,
            'token_type' => 'bearer',
            'expires_in' => (int) config('jwt.ttl', 60) * 60,
            'user' => $this->formatUser($user),
        ]);
    }

    #[OA\Get(
        path: '/api/me',
        operationId: 'getAuthenticatedUser',
        summary: 'Get authenticated user profile, roles, and permissions',
        security: [['bearerAuth' => []]],
        tags: ['Auth'],
        responses: [
            new OA\Response(response: 200, description: 'Authenticated user retrieved'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
        ]
    )]
    public function me(Request $request): JsonResponse
    {
        /** @var User|null $user */
        $user = $request->user('api') ?? auth('api')->user();

        if (! $user) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        return response()->json([
            'user' => $this->formatUser($user),
        ]);
    }

    #[OA\Post(
        path: '/api/refresh',
        operationId: 'refreshToken',
        summary: 'Refresh access token',
        security: [['bearerAuth' => []]],
        tags: ['Auth'],
        responses: [
            new OA\Response(response: 200, description: 'Token refreshed'),
            new OA\Response(response: 401, description: 'Unauthenticated or invalid token'),
        ]
    )]
    public function refresh(): JsonResponse
    {
        $token = auth('api')->refresh();

        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => (int) config('jwt.ttl', 60) * 60,
        ]);
    }

    #[OA\Post(
        path: '/api/logout',
        operationId: 'logoutUser',
        summary: 'Logout and invalidate token',
        security: [['bearerAuth' => []]],
        tags: ['Auth'],
        responses: [
            new OA\Response(response: 200, description: 'Logged out successfully'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
        ]
    )]
    public function logout(): JsonResponse
    {
        auth('api')->logout();

        return response()->json([
            'message' => 'Logged out successfully.',
        ]);
    }

    protected function formatUser(User $user): array
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'roles' => method_exists($user, 'getRoleNames')
                ? $user->getRoleNames()->values()->all()
                : [],
            'permissions' => method_exists($user, 'getAllPermissions')
                ? $user->getAllPermissions()->pluck('name')->values()->all()
                : [],
        ];
    }
}