<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventario extends Model
{
    use HasFactory;
    protected $fillable = [
    'cantidad',
    'codigo',
    'num',
    'gestion',
    'material_id',
    'compra_id',
    ];

    public function material(){
        return $this->belongsTo(Material::class);
    }

    public function compra(){
        return $this->belongsTo(Compra::class)->with('tienda');
    }

}
