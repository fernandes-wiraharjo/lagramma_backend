<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

class HamperSetting extends Model
{
    use HasFactory;

    protected $table = 'hampers_settings'; // Explicitly define the table name

    protected $fillable = [
        'product_id',
        'max_items',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function items()
    {
        return $this->belongsToMany(Product::class, 'hampers_setting_items', 'hampers_setting_id', 'product_id');
    }
}
