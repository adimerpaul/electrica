<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trabajodetalle extends Model
{
    use HasFactory;
    protected $fillable=[
        'cantidad',
        'material',
        'trabajo_id',
        'bodega_id',
        ];
}
