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

    public function tool()
    {
        return $this->has(boxtool::class);
    }}
