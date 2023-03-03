<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Salida extends Model
{
    use HasFactory;
    protected $fillable=[
    'destino',
    'motivo',
    'carro',
    'fecha',
    'hora',
    'tecnico_id',
    'user_id'
    ];

    public function tecnico()
    {
        return $this->belongsTo(User::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function elementos()
    {
        return $this->hasMany(Elemento::class)->with('inventario');
    }
}
