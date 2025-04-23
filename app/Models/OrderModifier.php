<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Order;
use App\Models\Modifier;
use App\Models\ModifierOption;
use App\Models\User;

class OrderModifier extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'modifier_id',
        'modifier_option_id',
        'modifier_name',
        'modifier_option_name',
        'created_by',
        'updated_by',
    ];

    /**
     * Relationships
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function modifier()
    {
        return $this->belongsTo(Modifier::class);
    }

    public function modifierOption()
    {
        return $this->belongsTo(ModifierOption::class);
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
