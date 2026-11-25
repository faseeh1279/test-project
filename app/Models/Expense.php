<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $fillable = ['category_id', 'title', 'amount', 'expense_date', 'notes', 'receipt_path'];

    protected $casts = ['expense_date' => 'date:Y-m-d', 'amount' => 'decimal:2'];

    public function user()     { return $this->belongsTo(User::class); }
    public function category() { return $this->belongsTo(Category::class); }
}
