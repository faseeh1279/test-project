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

class OpenApiSpec
{
}