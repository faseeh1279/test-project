<?php

namespace App\OpenApi;

use OpenApi\Attributes as OA;

#[OA\Info(
    version: '1.0.0',
    title: 'Expense & Receipt Tracker API',
    description: 'Personal expense tracking API with receipt uploads.'
)]
#[OA\Server(url: L5_SWAGGER_CONST_HOST, description: 'API server')]
#[OA\SecurityScheme(
    securityScheme: 'sanctum',
    type: 'http',
    scheme: 'bearer',
    bearerFormat: 'Sanctum token',
    description: 'Paste the token returned by /login or /register.'
)]
#[OA\Tag(name: 'Auth', description: 'Registration, login and session')]
#[OA\Tag(name: 'Categories', description: 'Expense categories')]
#[OA\Tag(name: 'Expenses', description: 'Expenses and receipts')]

// ---------- Reusable responses ----------
#[OA\Response(
    response: 'Unauthenticated',
    description: 'Missing or invalid token',
    content: new OA\JsonContent(properties: [
        new OA\Property(property: 'message', type: 'string', example: 'Unauthenticated.'),
    ])
)]
#[OA\Response(
    response: 'ValidationError',
    description: 'Validation failed',
    content: new OA\JsonContent(properties: [
        new OA\Property(property: 'message', type: 'string', example: 'The title field is required.'),
        new OA\Property(property: 'errors', type: 'object', example: ['title' => ['The title field is required.']]),
    ])
)]
#[OA\Response(
    response: 'NotFound',
    description: 'Resource not found',
    content: new OA\JsonContent(properties: [
        new OA\Property(property: 'message', type: 'string', example: 'Not Found.'),
    ])
)]

// ---------- Schemas ----------
#[OA\Schema(schema: 'User', properties: [
    new OA\Property(property: 'id', type: 'integer', example: 1),
    new OA\Property(property: 'name', type: 'string', example: 'Ali Khan'),
    new OA\Property(property: 'email', type: 'string', format: 'email', example: 'ali@example.com'),
    new OA\Property(property: 'created_at', type: 'string', format: 'date-time'),
    new OA\Property(property: 'updated_at', type: 'string', format: 'date-time'),
])]
#[OA\Schema(schema: 'AuthResponse', properties: [
    new OA\Property(property: 'user', ref: '#/components/schemas/User'),
    new OA\Property(property: 'token', type: 'string', example: '1|abcdef123456...'),
])]
#[OA\Schema(schema: 'Category', properties: [
    new OA\Property(property: 'id', type: 'integer', example: 3),
    new OA\Property(property: 'user_id', type: 'integer', example: 1),
    new OA\Property(property: 'name', type: 'string', example: 'Groceries'),
    new OA\Property(property: 'created_at', type: 'string', format: 'date-time'),
    new OA\Property(property: 'updated_at', type: 'string', format: 'date-time'),
])]
#[OA\Schema(schema: 'CategoryBrief', nullable: true, properties: [
    new OA\Property(property: 'id', type: 'integer', example: 3),
    new OA\Property(property: 'name', type: 'string', example: 'Groceries'),
])]
#[OA\Schema(schema: 'Expense', properties: [
    new OA\Property(property: 'id', type: 'integer', example: 12),
    new OA\Property(property: 'title', type: 'string', example: 'Weekly groceries'),
    new OA\Property(property: 'amount', type: 'string', example: '42.50'),
    new OA\Property(property: 'expense_date', type: 'string', format: 'date', example: '2026-11-25'),
    new OA\Property(property: 'notes', type: 'string', nullable: true),
    new OA\Property(property: 'category', ref: '#/components/schemas/CategoryBrief'),
    new OA\Property(property: 'receipt_url', type: 'string', nullable: true, example: 'http://localhost:8000/storage/receipts/abc.jpg'),
    new OA\Property(property: 'created_at', type: 'string', format: 'date-time'),
])]
#[OA\Schema(schema: 'ExpensePage', properties: [
    new OA\Property(property: 'data', type: 'array', items: new OA\Items(ref: '#/components/schemas/Expense')),
    new OA\Property(property: 'links', type: 'object'),
    new OA\Property(property: 'meta', type: 'object'),
])]
#[OA\Schema(schema: 'ExpenseSummary', properties: [
    new OA\Property(property: 'month', type: 'string', example: '2026-11'),
    new OA\Property(property: 'total', type: 'string', example: '1250.75'),
    new OA\Property(property: 'by_category', type: 'array', items: new OA\Items(properties: [
        new OA\Property(property: 'category', type: 'string', nullable: true, example: 'Groceries'),
        new OA\Property(property: 'total', type: 'string', example: '420.00'),
    ])),
])]
class Spec {}