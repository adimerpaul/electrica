<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory;
    protected $fillable = [
    'codigo',
    'nombre',
    'unidad',
    'stock',
    'minimo',
    'codificar',
    'color',
    'grupo_id',
    ];
    
    public function grupo()
    {
        return $this->belongsTo(Grupo::class);
    } 
}
