<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderDeliveryDetail extends Model
{
    protected $fillable = [
        'order_delivery_id',
        'weight',
        'width',
        'height',
        'length',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
    ];

    // Relationships
    public function orderDelivery()
    {
        return $this->belongsTo(OrderDelivery::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
