<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cronograma extends Model
{
    use HasFactory;
    protected $fillable=[
   'codigo',
   'actividad',
   //'distrito',
   //'junta',
   'celular',
   'tipo',
   'cantidad',
   'fecha',
   'descripcion',
   'junta_id'
    ];

    public function junta(){
        return $this->belongsTo(Junta::class);
    }
}
