<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ordendetalle extends Model
{
    use HasFactory;
    protected $fillable = [
        'cantidad',
        'unidad',
        'material',
        'estado',
        'entregado',
        'ordencompra_id',
        ];

    public function ordencompra()
    {
        return $this->belongsTo(Ordencompra::class)->with('tienda');
    } 

    public function logdetalle()
    {
        return $this->hasMany(Logdetalle::class);
    } 
}
