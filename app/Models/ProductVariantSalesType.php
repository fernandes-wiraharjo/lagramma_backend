<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ProductVariant;
use App\Models\SalesType;

class ProductVariantSalesType extends Model
{
    use HasFactory;

    protected $table = 'product_variant_sales_types'; // Explicitly define the table name

    protected $fillable = [
        'id_product_variant',
        'id_sales_type',
        'price',
        'is_default',
        'is_active',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at'
    ];

    // Relationship: A Sales Type belongs to a Variant
    public function productVariant()
    {
        return $this->belongsTo(ProductVariant::class, 'id_product_variant');
    }

    // Relationship: A Sales Type belongs to SalesTypes
    public function salesType()
    {
        return $this->belongsTo(SalesType::class, 'id_sales_type');
    }
}
