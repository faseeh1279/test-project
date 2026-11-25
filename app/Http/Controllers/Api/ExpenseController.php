<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ExpenseResource;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use OpenApi\Attributes as OA;

class ExpenseController extends Controller
{
    #[OA\Get(
    path: '/expenses',
    summary: 'List my expenses (paginated, filterable)',
    security: [['sanctum' => []]],
    tags: ['Expenses'],
    parameters: [
        new OA\Parameter(name: 'from', in: 'query', schema: new OA\Schema(type: 'string', format: 'date'), example: '2026-11-01'),
        new OA\Parameter(name: 'to', in: 'query', schema: new OA\Schema(type: 'string', format: 'date'), example: '2026-11-30'),
        new OA\Parameter(name: 'category_id', in: 'query', schema: new OA\Schema(type: 'integer')),
        new OA\Parameter(name: 'page', in: 'query', schema: new OA\Schema(type: 'integer', default: 1)),
    ],
    responses: [
        new OA\Response(response: 200, description: 'Paginated expenses', content: new OA\JsonContent(ref: '#/components/schemas/ExpensePage')),
        new OA\Response(ref: '#/components/responses/Unauthenticated', response: 401),
        new OA\Response(ref: '#/components/responses/ValidationError', response: 422),
    ]
)]
     public function index(Request $request)
    {
        $request->validate([
            'from'        => 'nullable|date',
            'to'          => 'nullable|date|after_or_equal:from',
            'category_id' => 'nullable|integer',
        ]);

        $expenses = $request->user()->expenses()
            ->with('category')
            ->when($request->from, fn ($q, $v) => $q->whereDate('expense_date', '>=', $v))
            ->when($request->to, fn ($q, $v) => $q->whereDate('expense_date', '<=', $v))
            ->when($request->category_id, fn ($q, $v) => $q->where('category_id', $v))
            ->orderByDesc('expense_date')
            ->orderByDesc('id')
            ->paginate(15);

        return ExpenseResource::collection($expenses);
    }
#[OA\Post(
    path: '/expenses',
    summary: 'Create an expense (optionally with a receipt image)',
    security: [['sanctum' => []]],
    tags: ['Expenses'],
    requestBody: new OA\RequestBody(required: true, content: new OA\MediaType(
        mediaType: 'multipart/form-data',
        schema: new OA\Schema(
            required: ['title', 'amount', 'expense_date'],
            properties: [
                new OA\Property(property: 'title', type: 'string', example: 'Weekly groceries'),
                new OA\Property(property: 'amount', type: 'number', format: 'float', example: 42.50),
                new OA\Property(property: 'expense_date', type: 'string', format: 'date', example: '2026-11-25'),
                new OA\Property(property: 'category_id', type: 'integer', nullable: true),
                new OA\Property(property: 'notes', type: 'string', nullable: true),
                new OA\Property(property: 'receipt', type: 'string', format: 'binary', description: 'jpg, jpeg, png or webp, max 5 MB'),
            ]
        )
    )),
    responses: [
        new OA\Response(response: 201, description: 'Created', content: new OA\JsonContent(properties: [
            new OA\Property(property: 'data', ref: '#/components/schemas/Expense'),
        ])),
        new OA\Response(ref: '#/components/responses/Unauthenticated', response: 401),
        new OA\Response(ref: '#/components/responses/ValidationError', response: 422),
    ]
)]
    public function store(Request $request)
    {
        $data = $this->validated($request);

        if ($request->hasFile('receipt')) {
            $data['receipt_path'] = $request->file('receipt')->store('receipts', 'public');
        }

        $expense = $request->user()->expenses()->create($data);

        return (new ExpenseResource($expense->load('category')))
            ->response()->setStatusCode(201);
    }
#[OA\Get(
    path: '/expenses/{expense}',
    summary: 'Get an expense',
    security: [['sanctum' => []]],
    tags: ['Expenses'],
    parameters: [new OA\Parameter(name: 'expense', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))],
    responses: [
        new OA\Response(response: 200, description: 'Expense', content: new OA\JsonContent(properties: [
            new OA\Property(property: 'data', ref: '#/components/schemas/Expense'),
        ])),
        new OA\Response(ref: '#/components/responses/Unauthenticated', response: 401),
        new OA\Response(ref: '#/components/responses/NotFound', response: 404),
    ]
)]
    public function show(Request $request, Expense $expense)
    {
        $this->authorizeOwner($request, $expense);
        return new ExpenseResource($expense->load('category'));
    }

    #[OA\Put(
    path: '/expenses/{expense}',
    summary: 'Update an expense (fields only)',
    security: [['sanctum' => []]],
    tags: ['Expenses'],
    parameters: [new OA\Parameter(name: 'expense', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))],
    requestBody: new OA\RequestBody(content: new OA\JsonContent(properties: [
        new OA\Property(property: 'title', type: 'string'),
        new OA\Property(property: 'amount', type: 'number', format: 'float'),
        new OA\Property(property: 'expense_date', type: 'string', format: 'date'),
        new OA\Property(property: 'category_id', type: 'integer', nullable: true),
        new OA\Property(property: 'notes', type: 'string', nullable: true),
    ])),
    responses: [
        new OA\Response(response: 200, description: 'Updated', content: new OA\JsonContent(properties: [
            new OA\Property(property: 'data', ref: '#/components/schemas/Expense'),
        ])),
        new OA\Response(ref: '#/components/responses/Unauthenticated', response: 401),
        new OA\Response(ref: '#/components/responses/NotFound', response: 404),
        new OA\Response(ref: '#/components/responses/ValidationError', response: 422),
    ]
)]
// Method-spoofed variant for uploading a new receipt (multipart can't be parsed on a real PUT)
#[OA\Post(
    path: '/expenses/{expense}',
    summary: 'Update an expense with a new receipt (POST + _method=PUT)',
    security: [['sanctum' => []]],
    tags: ['Expenses'],
    parameters: [new OA\Parameter(name: 'expense', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))],
    requestBody: new OA\RequestBody(required: true, content: new OA\MediaType(
        mediaType: 'multipart/form-data',
        schema: new OA\Schema(
            required: ['_method'],
            properties: [
                new OA\Property(property: '_method', type: 'string', enum: ['PUT'], example: 'PUT'),
                new OA\Property(property: 'title', type: 'string'),
                new OA\Property(property: 'amount', type: 'number', format: 'float'),
                new OA\Property(property: 'expense_date', type: 'string', format: 'date'),
                new OA\Property(property: 'category_id', type: 'integer', nullable: true),
                new OA\Property(property: 'notes', type: 'string', nullable: true),
                new OA\Property(property: 'receipt', type: 'string', format: 'binary'),
            ]
        )
    )),
    responses: [
        new OA\Response(response: 200, description: 'Updated', content: new OA\JsonContent(properties: [
            new OA\Property(property: 'data', ref: '#/components/schemas/Expense'),
        ])),
        new OA\Response(ref: '#/components/responses/Unauthenticated', response: 401),
        new OA\Response(ref: '#/components/responses/NotFound', response: 404),
        new OA\Response(ref: '#/components/responses/ValidationError', response: 422),
    ]
)]
    // From React Native, send this as POST with a `_method=PUT` field when uploading a file
    public function update(Request $request, Expense $expense)
    {
        $this->authorizeOwner($request, $expense);
        $data = $this->validated($request, partial: true);

        if ($request->hasFile('receipt')) {
            if ($expense->receipt_path) {
                Storage::disk('public')->delete($expense->receipt_path);
            }
            $data['receipt_path'] = $request->file('receipt')->store('receipts', 'public');
        }

        $expense->update($data);

        return new ExpenseResource($expense->load('category'));
    }
#[OA\Delete(
    path: '/expenses/{expense}',
    summary: 'Delete an expense (and its receipt file)',
    security: [['sanctum' => []]],
    tags: ['Expenses'],
    parameters: [new OA\Parameter(name: 'expense', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))],
    responses: [
        new OA\Response(response: 204, description: 'Deleted'),
        new OA\Response(ref: '#/components/responses/Unauthenticated', response: 401),
        new OA\Response(ref: '#/components/responses/NotFound', response: 404),
    ]
)]
    public function destroy(Request $request, Expense $expense)
    {
        $this->authorizeOwner($request, $expense);

        if ($expense->receipt_path) {
            Storage::disk('public')->delete($expense->receipt_path);
        }
        $expense->delete();

        return response()->noContent();
    }
#[OA\Get(
    path: '/expenses-summary',
    summary: 'Monthly total and per-category breakdown',
    security: [['sanctum' => []]],
    tags: ['Expenses'],
    parameters: [new OA\Parameter(name: 'month', in: 'query', description: 'Format Y-m. Defaults to the current month.', schema: new OA\Schema(type: 'string'), example: '2026-11')],
    responses: [
        new OA\Response(response: 200, description: 'Summary', content: new OA\JsonContent(ref: '#/components/schemas/ExpenseSummary')),
        new OA\Response(ref: '#/components/responses/Unauthenticated', response: 401),
        new OA\Response(ref: '#/components/responses/ValidationError', response: 422),
    ]
)]
    // GET /api/expenses-summary?month=2026-11
    public function summary(Request $request)
    {
        $request->validate(['month' => 'nullable|date_format:Y-m']);

        $month = \Carbon\Carbon::createFromFormat('Y-m', $request->month ?? now()->format('Y-m'));

        $base = $request->user()->expenses()
            ->whereBetween('expense_date', [$month->copy()->startOfMonth(), $month->copy()->endOfMonth()]);

        $byCategory = (clone $base)
            ->leftJoin('categories', 'categories.id', '=', 'expenses.category_id')
            ->selectRaw('categories.name as category, SUM(expenses.amount) as total')
            ->groupBy('categories.name')
            ->get();

        return [
            'month'       => $month->format('Y-m'),
            'total'       => (clone $base)->sum('amount'),
            'by_category' => $byCategory,
        ];
    }

    private function validated(Request $request, bool $partial = false): array
    {
        $required = $partial ? 'sometimes' : 'required';

        return $request->validate([
            'title'        => [$required, 'string', 'max:255'],
            'amount'       => [$required, 'numeric', 'min:0.01', 'max:99999999.99'],
            'expense_date' => [$required, 'date'],
            'notes'        => ['nullable', 'string', 'max:2000'],
            'category_id'  => ['nullable', Rule::exists('categories', 'id')
                                ->where('user_id', $request->user()->id)],
            'receipt'      => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);
    }

    private function authorizeOwner(Request $request, Expense $expense): void
    {
        abort_unless($expense->user_id === $request->user()->id, 404);
    }
}
