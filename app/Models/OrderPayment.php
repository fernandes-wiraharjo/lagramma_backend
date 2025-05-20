<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;

class OrderPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'vendor_invoice_id',
        'transaction_date',
        'status',
        'invoice_url',
        'payment_id',
        'payment_method',
        'bank_code',
        'payment_channel',
        'payment_destination',
        'paid_at',
        'expiry_date',
        'webhook_id',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at'
    ];

    /**
     * Relationships
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
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
