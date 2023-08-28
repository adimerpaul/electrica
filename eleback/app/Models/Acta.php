<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Acta extends Model
{
    use HasFactory;
    protected $fillable=[
        'fecha',
        'junta',
        'lugar',
        'distrito',
        'tipo',
        'luminaria',
        'cantidad',
        'tecnico',
        'archivo',
        'observacion',
        'vecino',
        'user_id',
        'junta_id'
        ];

        public function user(){
            return $this->belongsTo(User::class);
        }

        public function detailactas(){
            return $this->hasMany(detailactas::class);
        }

        public function junta(){
            return $this->belongsTo(junta::class);
        }

}
