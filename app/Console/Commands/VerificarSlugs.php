<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Trabajos;

class VerificarSlugs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'verificar:slugs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verificar los slugs generados para los trabajos';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 Verificando slugs de trabajos...');
        
        $trabajos = Trabajos::with(['cliente', 'servicio'])->get();
        
        if ($trabajos->isEmpty()) {
            $this->warn('No hay trabajos registrados.');
            return;
        }
        
        $this->table(
            ['ID', 'Cliente', 'Servicio', 'Slug'],
            $trabajos->map(function ($trabajo) {
                return [
                    $trabajo->id,
                    ($trabajo->cliente ? $trabajo->cliente->nombre . ' ' . $trabajo->cliente->apellido : 'N/A'),
                    ($trabajo->servicio ? $trabajo->servicio->nombreServicio : 'N/A'),
                    $trabajo->slug ?? 'Sin slug'
                ];
            })->toArray()
        );
        
        $this->info('✅ Verificación completada.');
    }
}
