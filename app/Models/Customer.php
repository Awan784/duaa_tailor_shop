<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use SoftDeletes;
    protected $table = 'customers';
    protected $fillable = ['name', 'email', 'phone', 'address', 'neck', 'shoulders', 'sleeve_length', 'length', 'sleeve_opening', 'chest', 'waist', 'hips', 'Asaan', 'Thighs', 'bottom_length', 'mori'];

    public function sales()
    {
        return $this->hasMany(Sale::class, 'customer_id');
    }

    public function shipments()
    {
        return $this->hasMany(Shipment::class, 'customer_id');
    }
    public function ledgers(){
        return $this->hasMany(Ledger::class, 'customer_id')->where('customer_type','customer');
    }
    public function getAccountSummaryAttribute()
    {
        $debit = $this->ledgers()->where('transaction_type', 'debit')->sum('amount');
        $credit = $this->ledgers()->where('transaction_type', 'credit')->sum('amount');
        $remaining = $credit - $debit;

        return [
            'debit' => $debit,
            'credit' => $credit,
            'remaining' => $remaining,
        ];
    }
}
