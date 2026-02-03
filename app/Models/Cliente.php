<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Cliente extends Model
{
   use HasFactory;

    protected $table = 'clientes';

    protected $fillable = [
        'tipo_cliente',
        'nombre_contacto',
        'empresa',
        'documento',
        'email',
        'telefono',
        'telefono_alterno',
        'pais',
        'ciudad',
        'direccion',
        'notas',
    ];

    public function getNombreMostrarAttribute(): string
    {
        return $this->tipo_cliente === 'empresa'
            ? ($this->empresa ?? $this->nombre_contacto)
            : $this->nombre_contacto;
    }

    public function garantias()
    {
        return $this->hasMany(Garantia::class);
    }
}
