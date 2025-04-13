<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MerchantProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'company_name',
        'address',
        'phone',
        'description',
        'logo',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function foodItems()
    {
        return $this->hasMany(FoodItem::class, 'merchant_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'merchant_id');
    }
}
