<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\AITextProcessingService;

class ProcessPostsContentsWithIa extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'posts:process-contents-with-ia';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Procesa el campo resumen de los posts con IA';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Implementa la lógica para procesar los cursos de postgrado con IA
        $posts = \App\Models\Post::all();

        $count = 0;

        foreach ($posts as $post) {
            // Solo procesar si el resumen está vacío
            if (empty($post->resumen)) {
                $prompt = "Necesito un resumen con un enfoque periodístico del texto indicado mas adelante en 55 palabras, con el objetivo de hacerlo mas corto en máximo un parrafo, usando el mismo tono que se usó originalmente en el texto, no repitas frases y usa un lenguaje natural y variado, asegurándote de que el mensaje sea claro y profesional. Sin añadir introducciones, comentarios o títulos de ninguno tipo. Asegúrate de que el resultado final tenga entre 40 y 55 palabras como mínimo y máximo. Si es necesario, prioriza la concisión sin omitir ideas clave. Estructura el contenido como un resumen breve y atractivo, con un lead potente ya que las primeras líneas deben captar la atención. Es muy importante que el texto final sea un texto directo, sin comillas, limitado a 55 palabras, en un solo párrafo. Para generar el contenido de resumen, debes usar la siguiente información: Título: {$post->titulo} y Contenido: {$post->texto_descriptivo_sin_html}";

                $resumen = $this->processPrompts($prompt);

                $post->update(
                    [
                        'resumen' => $resumen,
                    ],
                );

                $count++;
                $this->info("Se ha creado el resumen para el post {$post->id} de {$posts->count()}");
            } else {
                $this->info("El post {$post->id} ya tiene resumen, se omite.");
            }
        }

        $this->info("Proceso finalizado. {$count} posts actualizados con sus resumenes");
    }

    protected function processPrompts($prompt)
    {
        $service = new AITextProcessingService();
        return $service->processWithAI(
            $prompt,
            'Eres un escritor y periodista experto en sintetizar textos y crear extractos a partir de contenidos.'
        );
    }
}
