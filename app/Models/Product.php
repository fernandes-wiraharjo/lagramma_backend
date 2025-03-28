<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Category;
use App\Models\ProductVariant;
use App\Models\ProductModifier;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products'; // Explicitly define the table name

    protected $fillable = [
        'moka_id_product',
        'id_category',
        'name',
        'description',
        'is_sales_type_price',
        'is_active',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
    ];

    // Relationship: A Product belongs to a Category
    public function category()
    {
        return $this->belongsTo(Category::class, 'id_category');
    }

    // Relationship: A Product has many Variants
    public function variants()
    {
        return $this->hasMany(ProductVariant::class, 'id_product');
    }

    // Relationship: A Product has many Modifiers
    public function modifiers()
    {
        return $this->hasMany(ProductModifier::class, 'id_product');
    }
}
