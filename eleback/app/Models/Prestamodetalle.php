<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prestamodetalle extends Model
{
    use HasFactory;
    protected $fillable = [
        'fechadev',
        'horadev',
        'observacion',
        'usuario',
        'prestamo_id',
    ];
}
