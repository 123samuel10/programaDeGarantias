<?php
// app/Models/Garantia.php  (CORREGIDO)
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Garantia extends Model
{
    use HasFactory;

    protected $table = 'garantias';

    protected $fillable = [
        'cliente_id',
        'producto_id',
        'numero_serie',
        'fecha_compra',
        'fecha_vencimiento',
        'meses_garantia',
        'motivo',
        'notas',
        'estado',
    ];

    protected $casts = [
        'fecha_compra'      => 'date',
        'fecha_vencimiento' => 'date',
    ];

    public function cliente()
    {
        return $this->belongsTo(\App\Models\Cliente::class);
    }

    public function seguimientos()
    {
        return $this->hasMany(\App\Models\SeguimientoGarantia::class, 'garantia_id');
    }

    public function esFinal(): bool
    {
        return in_array($this->estado, ['cerrada','rechazada'], true);
    }

    public function estaVencidaPorFecha(): bool
    {
        if (!$this->fecha_vencimiento) return false;
        return now()->startOfDay()->gt($this->fecha_vencimiento->startOfDay());
    }

    /**
     * ✅ Estado MACRO consistente:
     * - cerrada/rechazada: no se toca
     * - si venció por fecha: vencida
     * - si tiene seguimientos: enproceso
     * - si no: activa
     */
    public function sincronizarEstadoMacro(): void
    {
        if ($this->esFinal()) return;

        if ($this->estaVencidaPorFecha()) {
            if ($this->estado !== 'vencida') {
                $this->estado = 'vencida';
                $this->save();
            }
            return;
        }

        $tieneSeguimientos = $this->seguimientos()->exists();
        $nuevo = $tieneSeguimientos ? 'enproceso' : 'activa';

        if ($this->estado !== $nuevo) {
            $this->estado = $nuevo;
            $this->save();
        }
    }
}
