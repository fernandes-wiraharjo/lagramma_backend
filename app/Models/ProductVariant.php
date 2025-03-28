<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;
use App\Models\ProductVariantSalesType;

class ProductVariant extends Model
{
    use HasFactory;

    protected $table = 'product_variants'; // Explicitly define the table name

    protected $fillable = [
        'moka_id_product_variant',
        'id_product',
        'name',
        'price',
        'stock',
        'track_stock',
        'position',
        'sku',
        'is_active',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at'
    ];

    // Relationship: A Variant belongs to a Product
    public function product()
    {
        return $this->belongsTo(Product::class, 'id_product');
    }

    // Relationship: A Variant has many Sales Types
    public function salesTypes()
    {
        return $this->hasMany(ProductVariantSalesType::class, 'id_product_variant');
    }
}
