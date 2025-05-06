<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderDelivery extends Model
{
    protected $fillable = [
        'order_id',
        'order_delivery_id',
        'order_delivery_no',
        'address_id',
        'date',
        'shipping_name',
        'shipping_type',
        'shipping_cost',
        'shipping_cashback',
        'service_fee',
        'grand_total',
        'is_send_to_other',
        'sto_pic_name',
        'sto_pic_phone',
        'sto_receiver_name',
        'sto_receiver_phone',
        'sto_note',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'is_send_to_other' => 'boolean',
        'date' => 'datetime',
    ];

    // Relationships
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function address()
    {
        return $this->belongsTo(UserAddress::class, 'address_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function details()
    {
        return $this->hasMany(OrderDeliveryDetail::class);
    }
}
