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
Crea 5 keywords separadas por comas para copiar y pegar en el meta keywords del código html de la página web del post indicado mas adelante.

Sigue las siguientes instrucciones:
- Analiza el contenido del post.
- Crea máximo 5 keywords relevantes.
- No uses frases o frases cortas.
- No agregues introducciones, comentarios o títulos de ninguno tipo.
- No uses caracteres especiales ni saltos de línea o similares como: /n, /r, /r/n, etc.
- usa sólamente minúsculas.
- separa las palabras con espacios y sin guiones.
- Asegúrate de que tu respuesta final incluya solamente las keywords SEPARADAS POR COMAS.

Post a analizar:
Título: {$post->title}
Resumen: {$post->resumen}
Contenido: {$post->texto_descriptivo_sin_html}
EOT;

                $tags = $this->processPrompts($prompt);

                $post->update(
                    [
                        'tags' => $tags,
                    ],
                );

                $count++;
                $this->info("Se han creado keywords para el post {$post->id} de {$posts->count()}");
            } else {
                $this->info("El post {$post->id} ya tiene keywords, se omite.");
            }
        }

        $this->info("Proceso finalizado. {$count} posts actualizados con sus keywords");
    }

    protected function processPrompts($prompt)
    {
        $service = new AITextProcessingService();
        return $service->processWithAI(
            $prompt,
            'Eres un experto SEO en crear keywords basados en contenidos de textos, contratado por la Camara Chilena de la Construcción, organización que promueve el desarrollo, innovación y sustentabilidad en el sector construcción, apoyando el crecimiento en Chile.'
        );
    }
}
