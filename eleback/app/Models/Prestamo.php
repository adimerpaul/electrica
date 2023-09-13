<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prestamo extends Model
{
    use HasFactory;
    protected $fillable = [
        'unidad',
        'destino',
        'fecha',
        'hora',
        'tiempo',
        'foto',
        'tecnico_id',
        'user_id',
    ];

    public function prestamodetalle()
    {
        return $this->hasMany(prestamodetalle::class);
    }

    public function tecnico()
    {
        return $this->belongsTo(user::class);
    }
}