<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\AITextProcessingService;

class ProcessPostsCategoriesWithIa extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'posts:process-categories-with-ia';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Procesa el campo categorías de los posts con IA';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Implementa la lógica para procesar los posts con IA
        $posts = \App\Models\Post::all();
        $categories = \App\Models\Category::all();

        $count = 0;

        foreach ($posts as $post) {
            // Solo procesar si el campo categorias está vacío
            if (empty($post->categorias)) {
                // Formatear las categorías de manera estructurada
                $categoriasList = $categories->map(function($category) {
                    return "{$category->name}: {$category->description}";
                })->join("\n");

                $prompt = <<<EOT
Necesito que me ayudes a categorizar el siguiente post usando las categorías disponibles.

Post a categorizar:
Título: {$post->title}
Resumen: {$post->resumen}
Contenido: {$post->texto_descriptivo_sin_html}

Categorías disponibles:
{$categoriasList}

Instrucciones:
1. Analiza cuidadosamente el contenido del post
2. Revisa la descripción de cada categoría para entender su alcance
3. Selecciona SOLO las categorías que verdaderamente correspondan al contenido
4. Devuelve SOLAMENTE los nombres de las categorías seleccionadas, separados por coma
5. NO agregues explicaciones adicionales

Ejemplo de formato de respuesta:
categoria1, categoria2, categoria3
EOT;

                $categorias = $this->processPrompts($prompt);

                $post->update(
                    [
                        'categorias' => $categorias,
                    ],
                );

                $count++;
                $this->info("Se han asignado las categorías para el post {$post->id} de {$posts->count()}");
            } else {
                $this->info("El post {$post->id} ya tiene categorías, se omite.");
            }
        }

        $this->info("Proceso finalizado. {$count} posts actualizados con sus categorías");
    }

    protected function processPrompts($prompt)
    {
        $service = new AITextProcessingService();
        return $service->processWithAI(
            $prompt,
            'Eres un escritor y periodista experto en catalogar y curar textos, , contratado por la Camara Chilena de la Construcción, organización que promueve el desarrollo, innovación y sustentabilidad en el sector construcción, apoyando el crecimiento en Chile.'
        );
    }
}
