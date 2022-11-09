<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    use HasFactory;
    protected $fillable=[
    "nombre",
    "ci",
    "telefono"
    ];

    public function reclamos()
    {
        return $this->hasMany(Reclamo::class)->with('poste');
    }
}
