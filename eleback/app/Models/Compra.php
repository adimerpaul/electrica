<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Compra extends Model
{
    use HasFactory;
    protected $fillable=[
    'nrocompra',
    'fecha',
    'gestion',
    'tienda_id'
    ];

    public function tienda(){
        return $this->belongsTo(Tienda::class);
    }

    public function contenidos(){
        return $this->hasMany(Contenido::class)->with('material');
    }

}
