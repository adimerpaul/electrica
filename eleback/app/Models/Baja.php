<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Baja extends Model
{
    use HasFactory;
    protected $fillable=[
        'motivo',
        'fecha',
        'hora',
        'usuario',
        'inventario_id',
        ];

        public function inventario(){
            return $this->belongsTo(Inventario::class);
        }
}
