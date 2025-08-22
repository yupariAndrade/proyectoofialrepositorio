<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Trabajos;
use Illuminate\Support\Str;

class GenerarSlugs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generar:slugs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generar slugs para todos los trabajos existentes';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔄 Generando slugs para trabajos existentes...');
        
        $trabajos = Trabajos::with(['cliente', 'servicio'])->get();
        
        if ($trabajos->isEmpty()) {
            $this->warn('No hay trabajos registrados.');
            return;
        }
        
        $bar = $this->output->createProgressBar($trabajos->count());
        $bar->start();
        
        foreach ($trabajos as $trabajo) {
            $cliente = $trabajo->cliente;
            $servicio = $trabajo->servicio;
            
            $baseSlug = Str::slug(
                ($cliente ? $cliente->nombre . ' ' . $cliente->apellido : 'trabajo') . 
                '-' . 
                ($servicio ? $servicio->nombreServicio : 'servicio') . 
                '-' . 
                $trabajo->id
            );
            
            $slug = $baseSlug;
            $counter = 1;
            
            // Asegurar que el slug sea único
            while (Trabajos::where('slug', $slug)->where('id', '!=', $trabajo->id)->exists()) {
                $slug = $baseSlug . '-' . $counter;
                $counter++;
            }
            
            $trabajo->update(['slug' => $slug]);
            $bar->advance();
        }
        
        $bar->finish();
        $this->newLine();
        $this->info('✅ Slugs generados exitosamente.');
        
        // Mostrar resultados
        $this->newLine();
        $this->info('📋 Trabajos con slugs generados:');
        $this->table(
            ['ID', 'Cliente', 'Servicio', 'Slug'],
            $trabajos->map(function ($trabajo) {
                return [
                    $trabajo->id,
                    ($trabajo->cliente ? $trabajo->cliente->nombre . ' ' . $trabajo->cliente->apellido : 'N/A'),
                    ($trabajo->servicio ? $trabajo->servicio->nombreServicio : 'N/A'),
                    $trabajo->slug
                ];
            })->toArray()
        );
    }
}
