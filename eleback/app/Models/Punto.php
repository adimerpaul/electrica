<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Punto extends Model
{
    use HasFactory;
    protected $fillable=[
        'fecha',
        'codigo',
        'nroposte',
        'descripcion',
        'potencia',
        'lat',
        'lng',
        'color',
        'estado',
    ];
}
