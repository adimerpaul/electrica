<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Poste extends Model
{
    use HasFactory;
    protected $fillable=[
    'lat',
    'lng',
    'luminaria',
    'nroposte',
    'material',
    'cantidad',
    'altura',
    'junta',
    'distrito',
    'potencia',
    'observacion',
    'color',
    'estado',
];

}
