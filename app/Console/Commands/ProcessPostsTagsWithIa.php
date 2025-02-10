<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\AITextProcessingService;

class ProcessPostsTagsWithIa extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'posts:process-tags-with-ia';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Procesa el campo tags de los posts con IA';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Implementa la lógica para procesar los posts con IA
        $posts = \App\Models\Post::all();

        $count = 0;

        foreach ($posts as $post) {
            // Solo procesar si el campo categorias está vacío
            if (empty($post->tags)) {

                $prompt = <<<EOT
Necesito que me ayudes a etiquetar el siguiente post con el objetivo de mejorar su contenido y su accesibilidad SEO.

Post a etiquetar:
Título: {$post->title}
Resumen: {$post->resumen}
Contenido: {$post->texto_descriptivo_sin_html}

Instrucciones:
1. Analiza cuidadosamente el contenido del post
2. Crea un listado de un máximo de 5 etiquetas que se aplican al post y sean relevantes para su posicionamiento SEO
3. Devuelve SOLAMENTE los nombres de las etiquetas, separados por coma
4. NO agregues explicaciones adicionales

Ejemplo de formato de respuesta:
etiqueta1, etiqueta2, etiqueta3
EOT;

                $tags = $this->processPrompts($prompt);

                $post->update(
                    [
                        'tags' => $tags,
                    ],
                );

                $count++;
                $this->info("Se han creado tags para el post {$post->id} de {$posts->count()}");
            } else {
                $this->info("El post {$post->id} ya tiene tags, se omite.");
            }
        }

        $this->info("Proceso finalizado. {$count} posts actualizados con sus tags");
    }

    protected function processPrompts($prompt)
    {
        $service = new AITextProcessingService();
        return $service->processWithAI(
            $prompt,
            'Eres un experto SEO dedicado a la industria de la construcción en etiquetado de textos, contratado por la Camara Chilena de la Construcción, organización que promueve el desarrollo, innovación y sustentabilidad en el sector construcción, apoyando el crecimiento en Chile.'
        );
    }
}
