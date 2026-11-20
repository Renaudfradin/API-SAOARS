<?php

namespace App\Services\Translation;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class ChatCatalogTranslationDriver implements CatalogTranslationDriver
{
    /**
     * @param  array<string, mixed>  $config
     */
    public function __construct(private array $config) {}

    public function label(): string
    {
        return (string) ($this->config['label'] ?? 'IA');
    }

    public function translate(array $french): array
    {
        return TranslationReply::decode($this->complete($french));
    }

    public function start(array $french): array
    {
        return [
            'agentId' => 'sync',
            'runId' => 'sync',
            'url' => '',
            'ready' => $this->translate($french),
        ];
    }

    public function check(string $agentId, string $runId): ?array
    {
        return null;
    }

    public function forget(string $agentId): void {}

    /**
     * @param  array<string, string>  $french
     */
    private function complete(array $french): string
    {
        $protocol = (string) ($this->config['protocol'] ?? 'openai');

        $response = match ($protocol) {
            'anthropic' => $this->send($this->anthropicPayload($french)),
            'gemini' => $this->send($this->geminiPayload($french), $this->geminiUrl()),
            default => $this->send($this->openaiPayload($french)),
        };

        if (! $response->successful()) {
            $message = $response->json('error.message') ?? $response->json('message');
            $detail = is_string($message) && $message !== '' ? ' : '.$message : '.';

            throw new RuntimeException($this->label().' a refusé la traduction'.$detail);
        }

        $path = match ($protocol) {
            'anthropic' => 'content.0.text',
            'gemini' => 'candidates.0.content.parts.0.text',
            default => 'choices.0.message.content',
        };

        $text = data_get($response->json(), $path);

        if (! is_string($text) || trim($text) === '') {
            throw new RuntimeException($this->label().' n’a pas renvoyé de traduction.');
        }

        return $text;
    }

    /**
     * @param  array<string, string>  $french
     * @return array<string, mixed>
     */
    private function openaiPayload(array $french): array
    {
        $payload = [
            'messages' => [
                ['role' => 'user', 'content' => TranslationPrompt::text($french)],
            ],
            'temperature' => 0.2,
        ];

        $model = $this->config['model'] ?? null;

        if (is_string($model) && $model !== '') {
            $payload['model'] = $model;
        }

        if (($this->config['json'] ?? false) === true) {
            $payload['response_format'] = ['type' => 'json_object'];
        }

        return $payload;
    }

    /**
     * @param  array<string, string>  $french
     * @return array<string, mixed>
     */
    private function anthropicPayload(array $french): array
    {
        return [
            'model' => (string) ($this->config['model'] ?? ''),
            'max_tokens' => 16000,
            'messages' => [
                ['role' => 'user', 'content' => TranslationPrompt::text($french)],
            ],
        ];
    }

    /**
     * @param  array<string, string>  $french
     * @return array<string, mixed>
     */
    private function geminiPayload(array $french): array
    {
        return [
            'contents' => [
                ['parts' => [['text' => TranslationPrompt::text($french)]]],
            ],
            'generationConfig' => [
                'temperature' => 0.2,
            ],
        ];
    }

    private function geminiUrl(): string
    {
        $model = rawurlencode((string) ($this->config['model'] ?? 'gemini-2.5-flash'));

        return rtrim((string) $this->config['url'], '/').'/'.$model.':generateContent';
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    private function send(array $payload, ?string $url = null): Response
    {
        $target = $url ?? (string) ($this->config['url'] ?? '');
        $auth = (string) ($this->config['auth'] ?? 'bearer');
        $key = $this->config['key'] ?? null;
        $keyName = (string) ($this->config['key_name'] ?? 'la clé API');

        if ($target === '') {
            $urlName = (string) ($this->config['url_name'] ?? 'l’URL');

            throw new RuntimeException('Définis '.$urlName.' pour traduire avec '.$this->label().'.');
        }

        if ($auth !== 'none' && (! is_string($key) || $key === '')) {
            throw new RuntimeException('Ajoute '.$keyName.' dans le fichier .env pour traduire avec '.$this->label().'.');
        }

        $pending = Http::acceptJson()->asJson()->timeout(120);

        $pending = match ($auth) {
            'api-key' => $pending->withHeaders(['api-key' => $key]),
            'anthropic' => $pending->withHeaders([
                'x-api-key' => $key,
                'anthropic-version' => '2023-06-01',
            ]),
            'query' => $pending,
            'none' => $pending,
            default => $pending->withToken((string) $key),
        };

        if ($auth === 'query') {
            $separator = str_contains($target, '?') ? '&' : '?';
            $target .= $separator.'key='.urlencode((string) $key);
        }

        return $pending->post($target, $payload);
    }
}
