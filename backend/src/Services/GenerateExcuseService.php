<?php

declare(strict_types=1);

class GenerateExcuseService
{
    private string $apiKey;
    private string $model = 'gpt-4o-mini';

    public function __construct()
    {
        $this->apiKey = $_ENV['OPENAI_API_KEY'] ?? '';
    }

    public function generate(
        string $context,
        string $objective,
        string $description,
        string $personType
    ): array {
        if ($this->apiKey === '') {
            return ['error' => 'openai_api_key_missing'];
        }

        $prompt   = $this->buildPrompt($context, $objective, $description, $personType);
        $response = $this->callOpenAI($prompt);

        if (isset($response['error'])) {
            return $response;
        }

        return $this->parseResponse($response['content']);
    }

    private function buildPrompt(
        string $context,
        string $objective,
        string $description,
        string $personType
    ): string {
        $contextLabels = [
            'work'         => 'laburo / trabajo',
            'relationship' => 'pareja',
            'friends'      => 'amigos',
        ];
        $objectiveLabels = [
            'cancel' => 'cancelar planes',
            'delay'  => 'postergar algo',
            'avoid'  => 'evitar una situación',
        ];

        $ctxLabel  = $contextLabels[$context]   ?? $context;
        $objLabel  = $objectiveLabels[$objective] ?? $objective;
        $extraLine = $description !== ''
            ? "Lo que me pasa puntualmente: {$description}\n"
            : "No dio más detalles de la situación.\n";
        $personLine = $personType !== ''
            ? "La otra persona es: {$personType}"
            : "No se sabe cómo es la otra persona.";

        return <<<PROMPT
Situación actual:
- Contexto: {$ctxLabel}
- Necesito: {$objLabel}
- {$extraLine}- {$personLine}

Basándote SOLO en estos datos (sin referencias a ninguna conversación anterior ni ejemplo genérico), creá una excusa creíble, empática y adaptada específicamente a esta situación.

La excusa tiene que:
1. Sonar 100% natural y argentino, como si la dijera una persona real
2. Ser específica a la situación planteada, no una respuesta de relleno
3. Adaptarse al perfil de la otra persona para que sea convincente para ella
4. Ayudar a la persona a salir airosa, no a complicarla más

Respondé ÚNICAMENTE con un JSON válido, sin markdown ni texto extra:
{
  "excuse": "La excusa completa para decir o escribir (2-4 oraciones, tono natural argentino)",
  "short_version": "Versión corta lista para mandar por WhatsApp (1 oración máximo)",
  "recommendation": "Consejo concreto de cómo y cuándo usarla para que quede bien con esa persona en particular"
}
PROMPT;
    }

    private function callOpenAI(string $prompt): array
    {
        $payload = json_encode([
            'model'       => $this->model,
            'messages'    => [
                [
                    'role'    => 'system',
                    'content' => 'Sos un asistente argentino experto en manejar situaciones sociales incómodas. Cada mensaje que recibís es una solicitud completamente nueva e independiente. No tenés memoria de conversaciones anteriores. Tu objetivo es ayudar a la persona a salir bien parada de su situación, de forma empática y con excusas creíbles.',
                ],
                [
                    'role'    => 'user',
                    'content' => $prompt,
                ],
            ],
            'temperature' => 0.85,
            'max_tokens'  => 600,
        ]);

        $ch = curl_init('https://api.openai.com/v1/chat/completions');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => $payload,
            CURLOPT_HTTPHEADER     => [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $this->apiKey,
            ],
            CURLOPT_TIMEOUT        => 30,
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlErr  = curl_error($ch);
        curl_close($ch);

        if ($response === false || $curlErr !== '') {
            return ['error' => 'openai_connection_failed'];
        }

        $data = json_decode($response, true);

        if ($httpCode !== 200) {
            return ['error' => $data['error']['message'] ?? 'openai_error'];
        }

        $content = $data['choices'][0]['message']['content'] ?? '';

        if ($content === '') {
            return ['error' => 'empty_response'];
        }

        return ['content' => $content];
    }

    private function parseResponse(string $content): array
    {
        $content = preg_replace('/^```(?:json)?\s*/i', '', trim($content));
        $content = preg_replace('/\s*```$/', '', $content);

        preg_match('/\{.*\}/s', $content, $matches);
        $json   = $matches[0] ?? '{}';
        $parsed = json_decode($json, true) ?? [];

        return [
            'excuse'         => $parsed['excuse']         ?? 'No se pudo generar la excusa.',
            'short_version'  => $parsed['short_version']  ?? '',
            'recommendation' => $parsed['recommendation'] ?? '',
        ];
    }
}
