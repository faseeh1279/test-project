<?php

namespace App\OpenApi;

use OpenApi\Attributes as OA;

#[OA\Info(
    title: 'Weather API',
    version: '1.0.0',
    description: 'A simple Laravel Weather API.'
)]

#[OA\Server(
    url: 'http://127.0.0.1:8000',
    description: 'Local development server'
)]

// #[OA\SecurityScheme(
//     securityScheme: 'bearerAuth',
//     type: 'http',
//     scheme: 'bearer',
//     bearerFormat: 'JWT',
//     description: 'Enter your JWT access token'
// )]

#[OA\SecurityScheme(
    securityScheme: 'apiKeyAuth',
    type: 'apiKey',
    in: 'header',
    name: 'X-API-TOKEN',
    description: 'Enter your website API token'
)]

class OpenApiSpec
{
}