<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Producto extends Model
{
    use HasFactory;

    protected $table = 'productos';

    protected $fillable = [
        'marca','modelo','descripcion','tipo_equipo'
    ];

    public function garantias()
    {
        return $this->hasMany(\App\Models\Garantia::class);
    }
}
