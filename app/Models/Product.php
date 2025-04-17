<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Category;
use App\Models\ProductVariant;
use App\Models\ProductModifier;
use App\Models\ProductImage;
use App\Models\ProductDeactivateDate;
use App\Models\HamperSetting;

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

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function mainImage()
    {
        return $this->hasOne(ProductImage::class)->where('is_main', true);
    }

    public function deactivateDates()
    {
        return $this->hasMany(ProductDeactivateDate::class);
    }

    public function hamperSetting()
    {
        return $this->hasOne(HamperSetting::class, 'product_id');
    }

    public function includedInHampers()
    {
        return $this->belongsToMany(HamperSetting::class, 'hampers_setting_items', 'product_id', 'hampers_setting_id');
    }
}
