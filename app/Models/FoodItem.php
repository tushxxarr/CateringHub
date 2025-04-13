<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FoodItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'merchant_id',
        'category_id',
        'name',
        'description',
        'price',
        'image',
        'is_available',
    ];

    protected $casts = [
        'is_available' => 'boolean',
    ];

    public function merchant()
    {
        return $this->belongsTo(MerchantProfile::class, 'merchant_id');
    }

    public function category()
    {
        return $this->belongsTo(FoodCategory::class, 'category_id');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
