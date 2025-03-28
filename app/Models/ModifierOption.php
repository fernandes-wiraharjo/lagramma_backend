<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Modifier;

class ModifierOption extends Model
{
    use HasFactory;

    protected $table = 'modifier_options'; // Explicitly define the table name

    protected $fillable = [
        'id_modifier',
        'moka_id_modifier_option',
        'name',
        'price',
        'position',
        'is_active',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at'
    ];

    public function modifier()
    {
        return $this->belongsTo(Modifier::class, 'id_modifier');
    }
}
