<?php

namespace App\Models;

use App\Models\Concerns\HasUuid;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasUuid;

    protected $fillable = [
        'invoice_number',
        'cashier_id',
        'customer_name',
        'subtotal',
        'discount',
        'total',
        'payment_method',
        'paid_amount',
        'status',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'discount' => 'decimal:2',
        'total' => 'decimal:2',
        'paid_amount' => 'decimal:2',
    ];

    public function cashier()
    {
        return $this->belongsTo(User::class, 'cashier_id');
    }

    public function items()
    {
        return $this->hasMany(SaleItem::class);
    }

    public function outstandingBalance(): float
    {
        return (float) $this->total - (float) $this->paid_amount;
    }
}
