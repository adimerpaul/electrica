<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventario extends Model
{
    use HasFactory;
    protected $fillable = [
    'cantidad',
    'codigo',
    'num',
    'material_id',
    'compra_id',
    ];
}
