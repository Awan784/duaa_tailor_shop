<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Shipment extends Model
{
    use SoftDeletes;

    protected $table = 'shipments';

    protected $fillable = [
        'customer_id',
        'shipment_number',
        'shipment_date',
        'charges',
        'description',
        'created_by',
    ];

    protected $casts = [
        'shipment_date' => 'date',
        'charges' => 'decimal:2',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function sales()
    {
        return $this->belongsToMany(Sale::class, 'shipment_sale')->withTimestamps();
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
