<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tool extends Model
{
    use HasFactory;
    protected $fillable = [
        'codigo',
        'nombre',
        'estado',
        'observacion',
        'usuario',
        'boxtool_id',
    ];

    public function boxtool()
    {
        return $this->belongsTo(boxtool::class);
    }
}
