<?php

namespace App\Console\Commands;

use App\Models\Category;
use Illuminate\Console\Command;

class ImportCategoriesFromCsv extends Command
{
    protected $signature = 'categories:import-csv';
    protected $description = 'Importa categorías desde el archivo CSV';

    public function handle()
    {
        $csvFile = database_path('categories.csv');
        
        if (!file_exists($csvFile)) {
            $this->error('El archivo CSV no existe en la ruta especificada.');
            return 1;
        }

        $this->info('Iniciando importación de categorías...');

        // Abrir el archivo CSV
        $file = fopen($csvFile, 'r');
        
        // Leer la primera línea para obtener los headers y convertirlos a minúsculas
        $headers = array_map('strtolower', fgetcsv($file));
        
        // Contadores para el progreso
        $created = 0;
        $updated = 0;
        
        // Crear una barra de progreso
        $bar = $this->output->createProgressBar();
        
        // Procesar cada línea del CSV
        while (($data = fgetcsv($file)) !== false) {
            $row = array_combine($headers, $data);
            
            // Actualizar si existe, crear si no existe
            $category = Category::where('name', $row['name'])->first();
            
            Category::updateOrCreate(
                ['name' => $row['name']],
                [
                    'name' => $row['name'],
                    'description' => $row['description'] ?? null,
                ]
            );
            
            if ($category) {
                $updated++;
            } else {
                $created++;
            }
            $bar->advance();
        }
        
        fclose($file);
        $bar->finish();
        
        $this->newLine();
        $this->info("Proceso completado: {$created} categorías creadas, {$updated} categorías actualizadas.");
        
        return 0;
    }
}
