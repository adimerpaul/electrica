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
    "telefono",
    "direccion",
    "zona",
    'distrito',
    'junta_id'];
}
