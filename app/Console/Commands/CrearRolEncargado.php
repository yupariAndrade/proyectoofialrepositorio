<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Roles;

class CrearRolEncargado extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'roles:crear-encargado';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crear el rol "encargado" en la base de datos';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Verificando si existe el rol "encargado"...');

        // Verificar si ya existe el rol
        $rolExistente = Roles::where('nombre', 'encargado')->first();

        if ($rolExistente) {
            $this->warn('El rol "encargado" ya existe en la base de datos.');
            $this->info('ID: ' . $rolExistente->id);
            $this->info('Nombre: ' . $rolExistente->nombre);
            return 0;
        }

        // Crear el rol encargado
        try {
            $rolEncargado = Roles::create([
                'nombre' => 'encargado'
            ]);

            $this->info('✅ Rol "encargado" creado exitosamente!');
            $this->info('ID: ' . $rolEncargado->id);
            $this->info('Nombre: ' . $rolEncargado->nombre);
            
            return 0;
        } catch (\Exception $e) {
            $this->error('❌ Error al crear el rol "encargado":');
            $this->error($e->getMessage());
            return 1;
        }
    }
}
