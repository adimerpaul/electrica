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
        'foto',
        'hora',
        'tiempo',
        'retorno',
        'codigo',
        'material',
        'estado',
        'observacion',
        'tool_id',
        'tecnico_id',
        'user_id',
    ];

    public function prestamodetalle()
    {
        return $this->hasMany(Prestamodetalle::class);
    }

    public function tecnico()
    {
        return $this->belongsTo(Tecnico::class);
    }

    public function tool()
    {
        return $this->belongsTo(Tool::class);
    }
}