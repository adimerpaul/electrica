<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Elemento extends Model
{
    use HasFactory;
    protected $fillable=[
    'cantidad',
    'material_id',
    'material',
    'salida_id',
    'inventario_id'
    ];

    public function inventario()
    {
        return $this->belongsTo(Inventario::class);
    }
}
