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
    'fechaplan',
    'estado',
    'tipo',
    'brazo',
    'comentario',
    'revisado',
];

    public function reclamos()
    {
        return $this->hasMany(Reclamo::class)->with('user');
    }
}
