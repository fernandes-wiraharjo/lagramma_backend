<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;
use App\Models\Modifier;

class ProductModifier extends Model
{
    use HasFactory;

    protected $table = 'product_modifiers'; // Explicitly define the table name

    protected $fillable = [
        'id_product',
        'id_modifier',
        'is_active',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at'
    ];

    // Relationship: A Modifier belongs to a Product
    public function product()
    {
        return $this->belongsTo(Product::class, 'id_product');
    }

    // Relationship: A Modifier belongs to a Modifier Table
    public function modifier()
    {
        return $this->belongsTo(Modifier::class, 'id_modifier');
    }
}
