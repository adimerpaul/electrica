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
        'telefono',
        'nueva',
        'mtto',
        'plantado',
        'repot',
        'otros',
        'fecha',
        'observacion',
        'estado',
        'user_id',
        'junta_id'
    ];
    
    public function user(){
        return $this->belongsTo(User::class);
    }
}
