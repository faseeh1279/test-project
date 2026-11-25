<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ExpenseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'id'           => $this->id,
            'title'        => $this->title,
            'amount'       => $this->amount,
            'expense_date' => $this->expense_date->toDateString(),
            'notes'        => $this->notes,
            'category'     => $this->whenLoaded('category', fn () => $this->category ? [
                'id' => $this->category->id, 'name' => $this->category->name,
            ] : null),
            'receipt_url'  => $this->receipt_path ? Storage::disk('public')->url($this->receipt_path) : null,
            'created_at'   => $this->created_at,
        ];
    }
}
