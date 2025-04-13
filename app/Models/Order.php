<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'merchant_id',
        'order_number',
        'total_amount',
        'status',
        'delivery_date',
        'delivery_time',
        'delivery_address',
        'notes',
    ];

    public function customer()
    {
        return $this->belongsTo(CustomerProfile::class, 'customer_id');
    }

    public function merchant()
    {
        return $this->belongsTo(MerchantProfile::class, 'merchant_id');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }
}
