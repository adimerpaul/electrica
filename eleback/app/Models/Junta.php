<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Junta extends Model
{
    use HasFactory;
    protected $fillable=[
        'nombre',
        'distrito',
        'zona',
        'representante',
        'celular'
    ];

}
