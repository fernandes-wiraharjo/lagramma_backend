<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ModifierOption;

class Modifier extends Model
{
    use HasFactory;

    protected $table = 'modifiers'; // Explicitly define the table name

    protected $fillable = [
        'moka_id_modifier',
        'name',
        'is_active',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at'
    ];

    public function options()
    {
        return $this->hasMany(ModifierOption::class, 'id_modifier');
    }
}
