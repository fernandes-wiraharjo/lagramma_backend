<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

class ProductDeactivateDate extends Model
{
    use HasFactory;

    protected $table = 'product_deactivate_dates'; // Explicitly define the table name

    protected $fillable = [
        'product_id',
        'start_date',
        'end_date',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
