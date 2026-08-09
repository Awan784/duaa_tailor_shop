<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BusinessExpense extends Model
{
    use SoftDeletes;

    protected $table = 'business_expenses';

    protected $fillable = [
        'title',
        'amount',
        'expense_date',
        'description',
        'created_by',
    ];

    protected $casts = [
        'expense_date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
