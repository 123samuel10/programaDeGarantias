<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Garantia;

class MarcarGarantiasVencidas extends Command
{
  protected $signature = 'garantias:marcar-vencidas';
    protected $description = 'Marca como vencidas las garantías cuya fecha de vencimiento ya pasó';

    public function handle(): int
    {
        $hoy = now()->toDateString();

        Garantia::whereNotIn('estado', ['cerrada', 'rechazada'])
            ->whereDate('fecha_vencimiento', '<', $hoy)
            ->update(['estado' => 'vencida']);

        return self::SUCCESS;
    }
}
