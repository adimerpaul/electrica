<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ordencompra extends Model
{
    use HasFactory;
    protected $fillable = [
    'norden',
    'fecha',
    'responsable',
    'tienda_id',
    'user_id'
    ];
   
    public function ordendetalle()
    {
        return $this->hasMany(Ordendetalle::class);
    } 
    public function tienda()
    {
        return $this->belongsTo(Tienda::class);
    } 
}
