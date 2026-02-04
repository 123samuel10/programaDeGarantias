<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    protected $table = 'productos';

    protected $fillable = [
        'marca',
        'modelo',
        'nombre_producto',
        'tipo_equipo',
        'descripcion',
        'foto',
        'repisas_iluminadas',
        'refrigerante',
        'longitud',
        'profundidad',
        'altura',
    ];
}
