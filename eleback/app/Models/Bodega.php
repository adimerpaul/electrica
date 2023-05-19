<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bodega extends Model
{
    use HasFactory;
    protected $fillable=[
    'material',
    'cantidad',
    'saldo',
    'estado',
    'material_id',
    'inventario_id',
    'user_id'
    ];

    public function material(){
        return $this->belongsTo(Material::class);
    }

    public function inventario(){
        return $this->belongsTo(Inventario::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
