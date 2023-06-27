<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Devuelta extends Model
{
    use HasFactory;
    protected $fillable=[
        'cantidad',
        'material',
        'fecha',
        'hora',
        'inventario_id',
        'tecnico_id',
        'user_id',
        ];

}
