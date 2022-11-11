<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reclamo extends Model
{
    use HasFactory;
    protected $fillable=[
    'reclamo',
    'fecha',
    'hora',
    'fechaman',
    'horaman',
    "estado",
    "tecnico",
    "supervisor",
    "actividad",
    'tipo',
    'persona_id',
    'poste_id',
    "l70",
    "l150",
    "l250",
    "r70",
    "r150",
    "r250",
    "foto",
    "base",
    "ignitor",
    "cable",
    "contactor",
    "termico",
    "soquet",
    ];

    public function poste()
    {
        return $this->belongsTo(Poste::class);
    }

    public function persona()
    {
        return $this->belongsTo(Persona::class);
    }
}
