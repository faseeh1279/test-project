<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse; 
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
                                new OA\Property(
                                    property: 'city',
                                    type: 'string',
                                    example: 'Lahore'
                                ),
                                new OA\Property(
                                    property: 'temperature',
                                    type: 'integer',
                                    example: 29
                                ),
                                new OA\Property(
                                    property: 'condition',
                                    type: 'string',
                                    example: 'Partly Cloudy'
                                ),
                                new OA\Property(
                                    property: 'humidity',
                                    type: 'integer',
                                    example: 65
                                )
                            ]
                        )
                    )
                ],
                type: 'object'
            )
        )
    ]
)]
    public function index(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => array_values($this->weatherData),
        ]);
    }
#[OA\Get(
    path: '/api/weather/{city}',
    operationId: 'getWeatherByCity',
    summary: 'Get weather for a specific city',
    tags: ['Weather'],
    parameters: [
        new OA\Parameter(
            name: 'city',
            in: 'path',
            required: true,
            description: 'Name of the city',
            schema: new OA\Schema(type: 'string'),
            example: 'Lahore'
        )
    ],
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
                        type: 'object',
                        properties: [
                            new OA\Property(
                                property: 'city',
                                type: 'string',
                                example: 'Lahore'
                            ),
                            new OA\Property(
                                property: 'temperature',
                                type: 'integer',
                                example: 29
                            ),
                            new OA\Property(
                                property: 'condition',
                                type: 'string',
                                example: 'Partly Cloudy'
                            ),
                            new OA\Property(
                                property: 'humidity',
                                type: 'integer',
                                example: 65
                            )
                        ]
                    )
                ],
                type: 'object'
            )
        ),
        new OA\Response(
            response: 404,
            description: 'Weather data not found'
        )
    ]
)]

    public function show(string $city): JsonResponse
    {
        $city = strtolower($city);

        if (!isset($this->weatherData[$city])) {
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
                    new OA\Property(
                        property: 'success',
                        type: 'boolean',
                        example: true
                    ),
                    new OA\Property(
                        property: 'message',
                        type: 'string',
                        example: 'Weather API is running.'
                    )
                ],
                type: 'object'
            )
        )
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
    operationId: 'echoCredentials',
    summary: 'Echo username and password',
    description: 'Accepts a username and password and returns the same values.',
    tags: ['Weather'],
    requestBody: new OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ['username', 'password'],
            properties: [
                new OA\Property(
                    property: 'username',
                    type: 'string',
                    example: 'john_doe'
                ),
                new OA\Property(
                    property: 'password',
                    type: 'string',
                    example: 'MyPassword123'
                )
            ]
        )
    ),
    responses: [
        new OA\Response(
            response: 200,
            description: 'Returns the submitted username and password',
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(
                        property: 'username',
                        type: 'string',
                        example: 'john_doe'
                    ),
                    new OA\Property(
                        property: 'password',
                        type: 'string',
                        example: 'MyPassword123'
                    )
                ]
            )
        ), 
        new OA\Response(
    response: 403,
    description: 'Invalid username. Access denied.',
    content: new OA\JsonContent(
        properties: [
            new OA\Property(
                property: 'success',
                type: 'boolean',
                example: false
            ),
            new OA\Property(
                property: 'message',
                type: 'string',
                example: 'Invalid username. Access denied.'
            )
        ]
    )
)
    ]
)]
    public function store(Request $request): JsonResponse
    {
        if ($request->input('username') !== 'faseehraza1279') {
            return response()->json([
                'success' => false,
                'message' => 'Invalid username. Access denied.',
            ], 403);
        }

        return response()->json([
            'username' => $request->input('username'),
            'password' => $request->input('password'),
        ]);
    }
}
