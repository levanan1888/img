<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number', 'user_id', 'total_amount', 'payment_status', 'payment_method', 'shipping_address'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function timeline()
    {
        return $this->hasMany(OrderStatusTimeline::class)->orderBy('created_at', 'asc');
    }

    public function latestStatus()
    {
        return $this->hasOne(OrderStatusTimeline::class)->latestOfMany();
    }
}
