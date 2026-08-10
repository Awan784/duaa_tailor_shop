<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;

class Sale extends Model
{
    use SoftDeletes;
    protected $table = 'sales';
    protected $fillable = ['customer_id', 'tailor_id', 'date', 'bill_no', 'status', 'products', 'sub_total', 'labour_cost', 'discount', 'net_total', 'description', 'images', 'created_by'];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function ledger()
    {
        return $this->hasMany(Ledger::class, 'sale_id');
    }

    public function shipments()
    {
        return $this->belongsToMany(Shipment::class, 'shipment_sale')->withTimestamps();
    }
    public function ledgerAmount()
    {
        return $this->ledger->sum('amount');
    }
    public function getProducts()
    {
        // Decode the JSON products column
        $products = json_decode($this->products, true);
        // Fetch stock names based on product IDs
        $productDetails = [];
        if ($products) {
            foreach ($products as $product) {
                $stock = Stock::find($product['product_id']);
                if ($stock) {
                    $productDetails[] = [
                        'product_id' => $product['product_id'],
                        'name' => $stock->name,
                        'remaining_quantities' => $stock->remaining_quantity(),
                        'quantity' => $product['quantity'],
                        'unit_price' => $product['unit_price'],
                        'sub_total' => $product['sub_total'],
                        'sku' => $product['sku'] ?? 'undefined'
                    ];
                }
            }
        }

        return $productDetails;
    }
}
