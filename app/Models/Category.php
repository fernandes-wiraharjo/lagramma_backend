<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

class Category extends Model
{
    use HasFactory;

    protected $table = 'categories'; // Explicitly define the table name

    protected $fillable = [
        'moka_id_category',
        'name',
        'description',
        'is_active',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at'
    ];

    // Relationship: A Category has many Products
    public function products()
    {
        return $this->hasMany(Product::class, 'id_category');
    }
}
