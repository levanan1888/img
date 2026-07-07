<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderStatusTimeline extends Model
{
    use HasFactory;

    protected $table = 'order_status_timeline';

    public $timestamps = false;

    protected $fillable = ['order_id', 'status', 'comment', 'changed_by', 'created_at'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function modifier()
    {
        return $this->belongsTo(User::class, 'changed_by')->withDefault([
            'name' => 'System'
        ]);
    }
}
