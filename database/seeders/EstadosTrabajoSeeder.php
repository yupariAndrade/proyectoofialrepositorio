<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EstadosTrabajoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $estados = [
            ['id' => 1, 'nombre' => 'Pendiente'],
            ['id' => 2, 'nombre' => 'En Proceso'],
            ['id' => 3, 'nombre' => 'Completado'],
            ['id' => 4, 'nombre' => 'Cancelado'],
        ];

        foreach ($estados as $estado) {
            \App\Models\EstadosTrabajo::updateOrCreate(
                ['id' => $estado['id']],
                ['nombre' => $estado['nombre']]
            );
        }
    }
}
