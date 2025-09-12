<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Trabajos;

class GenerateSlugs extends Command
{
    protected $signature = 'generate:slugs';
    protected $description = 'Generate slugs for existing trabajos';

    public function handle()
    {
        $trabajos = Trabajos::whereNull('slug')->orWhere('slug', '')->get();
        
        $this->info("Found {$trabajos->count()} trabajos without slugs");
        
        foreach ($trabajos as $trabajo) {
            $trabajo->slug = $trabajo->generateSlug();
            $trabajo->save();
            $this->line("Generated slug for trabajo ID {$trabajo->id}: {$trabajo->slug}");
        }
        
        $this->info('All slugs generated successfully!');
    }
}