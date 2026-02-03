<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\Garantia;

class GarantiasMarcarVencidas extends Command
{
    protected $signature = 'garantias:marcar-vencidas';
    protected $description = 'Marca garantías vencidas cuando ya pasó la fecha de vencimiento';

    public function handle(): int
    {
        $n = Garantia::whereNotIn('estado', ['cerrada','rechazada','vencida'])
            ->whereDate('fecha_vencimiento', '<', now()->toDateString())
            ->update(['estado' => 'vencida']);

        $this->info("Garantías marcadas como vencidas: {$n}");
        return self::SUCCESS;
    }
}
