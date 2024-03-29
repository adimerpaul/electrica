<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Denuncia extends Model
{
    use HasFactory;
    protected $fillable=[
        "nombre",
        "ci",
        "telefono",
        "direccion",
        "zona",
        "nroposte",
        "reclamo",
        "lat",
        "lng",
        "fecha",
        "hora",
        "estado",
        "tecnico",
        "supervisor",
        "fechaman",
        "horaman",
        "actividad",
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
}
