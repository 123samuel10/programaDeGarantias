protected function schedule(\Illuminate\Console\Scheduling\Schedule $schedule)
{
    // ✅ Corre todos los días a las 00:05
    $schedule->command('garantias:marcar-vencidas')->dailyAt('00:05');
}
