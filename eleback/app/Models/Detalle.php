<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Detalle extends Model
{
    use HasFactory;
    protected $fillable=[
        'cantidad',
        'material',
        'poste_id',
        'reclamo_id',
        'bodega_id',
        ];

}
