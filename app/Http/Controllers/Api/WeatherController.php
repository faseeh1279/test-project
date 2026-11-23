<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class WeatherController extends Controller
{
    private array $weatherData = [
        'london' => [
            'city' => 'London',
            'temperature' => 18,
            'condition' => 'Cloudy',
            'humidity' => 75,
        ],
        'karachi' => [
            'city' => 'Karachi',
            'temperature' => 32,
            'condition' => 'Sunny',
            'humidity' => 60,
        ],
        'lahore' => [
            'city' => 'Lahore',
            'temperature' => 29,
            'condition' => 'Partly Cloudy',
            'humidity' => 65,
        ],
    ];

    #[OA\Get(
        path: '/api/weather',
        operationId: 'getAllWeather',
        summary: 'Get weather for all cities',
        security: [['bearerAuth' => []]],
        tags: ['Weather'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Weather data retrieved successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'success',
                            type: 'boolean',
                            example: true
                        ),
                        new OA\Property(
                            property: 'data',
                            type: 'array',
                            items: new OA\Items(
                                type: 'object',
                                properties: [
                                    new OA\Property(property: 'city', type: 'string', example: 'Lahore'),
                                    new OA\Property(property: 'temperature', type: 'integer', example: 29),
                                    new OA\Property(property: 'condition', type: 'string', example: 'Partly Cloudy'),
                                    new OA\Property(property: 'humidity', type: 'integer', example: 65),
                                ]
                            )
                        ),
                    ],
                    type: 'object'
                )
            ),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 403, description: 'Forbidden'),
        ]
    )]
    public function index(Request $request): JsonResponse
    {
        /** @var User|null $user */
        $user = $request->user('api') ?? auth('api')->user();

        if (! $user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.',
            ], 401);
        }

        if (! $user->can('view weather')) {
            return response()->json([
                'success' => false,
                'message' => 'Forbidden. Missing view weather permission.',
            ], 403);
        }

        return response()->json([
            'success' => true,
            'data' => array_values($this->weatherData),
        ]);
    }

    #[OA\Get(
        path: '/api/weather/{city}',
        operationId: 'getWeatherByCity',
        summary: 'Get weather for a specific city',
        security: [['bearerAuth' => []]],
        tags: ['Weather'],
        parameters: [
            new OA\Parameter(
                name: 'city',
                in: 'path',
                required: true,
                description: 'Name of the city',
                schema: new OA\Schema(type: 'string'),
                example: 'Lahore'
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Weather data retrieved successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(
                            property: 'data',
                            type: 'object',
                            properties: [
                                new OA\Property(property: 'city', type: 'string', example: 'Lahore'),
                                new OA\Property(property: 'temperature', type: 'integer', example: 29),
                                new OA\Property(property: 'condition', type: 'string', example: 'Partly Cloudy'),
                                new OA\Property(property: 'humidity', type: 'integer', example: 65),
                            ]
                        ),
                    ],
                    type: 'object'
                )
            ),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 403, description: 'Forbidden'),
            new OA\Response(response: 404, description: 'Weather data not found'),
        ]
    )]
    public function show(Request $request, string $city): JsonResponse
    {
        /** @var User|null $user */
        $user = $request->user('api') ?? auth('api')->user();

        if (! $user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.',
            ], 401);
        }

        if (! $user->can('view weather')) {
            return response()->json([
                'success' => false,
                'message' => 'Forbidden. Missing view weather permission.',
            ], 403);
        }

        $city = strtolower($city);

        if (! isset($this->weatherData[$city])) {
            return response()->json([
                'success' => false,
                'message' => 'Weather data not found for this city.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $this->weatherData[$city],
        ]);
    }

    #[OA\Get(
        path: '/api/weather/health',
        operationId: 'checkWeatherApiHealth',
        summary: 'Check whether the Weather API is running',
        tags: ['Weather'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Weather API is running',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'message', type: 'string', example: 'Weather API is running.'),
                    ],
                    type: 'object'
                )
            ),
        ]
    )]
    public function health(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Weather API is running.',
        ]);
    }

    #[OA\Post(
        path: '/api/weather',
        operationId: 'storeWeatherEntry',
        summary: 'Create or submit weather data (authorized users only)',
        description: 'Requires a valid Bearer JWT and the create weather permission (or admin role).',
        security: [['bearerAuth' => []]],
        tags: ['Weather'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['city', 'temperature', 'condition', 'humidity'],
                properties: [
                    new OA\Property(property: 'city', type: 'string', example: 'Gujranwala'),
                    new OA\Property(property: 'temperature', type: 'integer', example: 27),
                    new OA\Property(property: 'condition', type: 'string', example: 'Clear'),
                    new OA\Property(property: 'humidity', type: 'integer', example: 58),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Weather entry created',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'message', type: 'string', example: 'Weather record accepted.'),
                        new OA\Property(
                            property: 'data',
                            type: 'object',
                            properties: [
                                new OA\Property(property: 'city', type: 'string', example: 'Gujranwala'),
                                new OA\Property(property: 'temperature', type: 'integer', example: 27),
                                new OA\Property(property: 'condition', type: 'string', example: 'Clear'),
                                new OA\Property(property: 'humidity', type: 'integer', example: 58),
                            ]
                        ),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 403, description: 'Forbidden. Insufficient role or permission.'),
            new OA\Response(response: 422, description: 'Validation error'),
        ]
    )]
    public function store(Request $request): JsonResponse
    {
        /** @var User|null $user */
        $user = $request->user('api') ?? auth('api')->user();

        if (! $user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.',
            ], 401);
        }

        if (! $user->can('create weather') && ! $user->hasRole('admin')) {
            return response()->json([
                'success' => false,
                'message' => 'Forbidden. You do not have permission to create weather records.',
            ], 403);
        }

        $validated = $request->validate([
            'city' => ['required', 'string', 'max:100'],
            'temperature' => ['required', 'integer', 'between:-80,60'],
            'condition' => ['required', 'string', 'max:100'],
            'humidity' => ['required', 'integer', 'between:0,100'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Weather record accepted.',
            'data' => $validated,
        ], 201);
    }
}