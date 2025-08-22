<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Barryvdh\DomPDF\Facade\Pdf;

class TestPDF extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:pdf';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test DomPDF functionality';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $this->info('Testing DomPDF...');
            
            // Test simple HTML
            $html = '<h1>Test PDF</h1><p>This is a test PDF generated with DomPDF.</p>';
            
            $pdf = Pdf::loadHTML($html);
            
            $this->info('PDF generated successfully!');
            
            // Save to storage for testing
            $pdf->save(storage_path('app/test.pdf'));
            
            $this->info('PDF saved to: ' . storage_path('app/test.pdf'));
            
            return 0;
        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
            $this->error('Stack trace: ' . $e->getTraceAsString());
            return 1;
        }
    }
}
