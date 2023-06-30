<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trabajo extends Model
{
    use HasFactory;
    protected $fillable=[
        'lat',
        'lng',
        'lugar',
        'fecha',
        'hora',
        'actividad',
        'tipo',
        'user_id',
        ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function trabajodetalle(){
        return $this->hasMany(Trabajodetalle::class);
    }
}
