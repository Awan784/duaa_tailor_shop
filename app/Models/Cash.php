<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cash extends Model
{
    use SoftDeletes;

    protected $table = 'cashes';

    protected $fillable = [
        'type',
        'amount',
        'cash_date',
        'description',
        'created_by',
    ];

    protected $casts = [
        'cash_date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getTypeLabelAttribute(): string
    {
        return $this->type === 'cash_payment' ? 'Cash Payment' : 'Cash Receive';
    }
}
