<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agendadetalle extends Model
{
    use HasFactory;
    protected $fillable=[
        'cantidad',
        'tipo',
        'potencia',
        'agenda_id'
    ];
}
