<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agenda extends Model
{
    use HasFactory;
    protected $fillable=[
        'distrito',
        'junta',
        'dirigente',
        'cargo',
        'telefono',
        /*'nueva',
        'mtto',
        'plantado',
        'repot',
        'otros',
        'cantidad',
        'tipo',
        'potencia',
        */
        'fecha',
        'fechaprog',
        'observacion',
        'estado',
        'user_id',
        //'junta_id'
    ];
    
    public function user(){
        return $this->belongsTo(User::class);
    }

        
    public function agendadetalles(){
        return $this->hasMany(Agendadetalle::class);
    }
}
