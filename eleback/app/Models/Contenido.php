<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contenido extends Model
{
    use HasFactory;
    protected $fillable=[
    'compra_id',
    'material_id',
    'cantidad',
    'unitario',
    'total'];

    public function material(){
        return $this->belongsTo(Material::class);
    }

}
