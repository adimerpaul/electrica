<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Boxtool extends Model
{
    use HasFactory;
    protected $fillable = [
        'stock',
        'disponible',
        'nombre',
    ];

    public function tools()
    {
        return $this->hasMany(Tool::class);
    }

    public function toolsactivo()
    {
        return $this->hasMany(Tool::class)->where('estado','ACTIVO');
    }
}
