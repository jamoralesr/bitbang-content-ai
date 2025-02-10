<?php

namespace App\Services;

use EchoLabs\Prism\Prism;
use EchoLabs\Prism\Enums\Provider;

class AITextProcessingService
{
    public function processWithAI(string $prompt, string $systemPrompt, int $maxTokens = 370): string
    {
        $response = Prism::text()
            ->using(Provider::Ollama, 'llama3.2:latest')
            ->withSystemPrompt($systemPrompt)
            ->withMaxTokens($maxTokens)
            ->withPrompt($prompt)
            ->withClientOptions(['timeout' => 120])
            ->generate();

        return $response->text;
    }
}
