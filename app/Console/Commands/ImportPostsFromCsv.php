<?php

namespace App\Console\Commands;

use App\Models\Post;
use Illuminate\Console\Command;

class ImportPostsFromCsv extends Command
{
    protected $signature = 'posts:import-csv';
    protected $description = 'Importa posts desde el archivo CSV de CChC';

    public function handle()
    {
        $csvFile = database_path('CChC-2019-2023-Noticias.csv');
        
        if (!file_exists($csvFile)) {
            $this->error('El archivo CSV no existe en la ruta especificada.');
            return 1;
        }

        $this->info('Iniciando importación de posts...');

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
            $post = Post::where('entry_id', $row['entry_id'])->first();
            
            Post::updateOrCreate(
                ['entry_id' => $row['entry_id'] ?? null],
                [
                'entry_id' => $row['entry_id'] ?? null,
                'url' => $row['url'] ?? null,
                'title' => $row['title'] ?? null,
                'resumen' => $row['resumen'] ?? null,
                'texto_descriptivo' => $row['texto_descriptivo'] ?? null,
                'texto_descriptivo_sin_html' => $row['texto_descriptivo_sin_html'] ?? null,
                'regional' => $row['regional'] ?? null,
                'temas' => $row['temas'] ?? '',
                ]
            );
            
            if ($post) {
                $updated++;
            } else {
                $created++;
            }
            $bar->advance();
        }
        
        fclose($file);
        $bar->finish();
        
        $this->newLine();
        $this->info("Proceso completado: {$created} posts creados, {$updated} posts actualizados.");
        
        return 0;
    }
}
