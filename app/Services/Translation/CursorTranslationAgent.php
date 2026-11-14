<?php

namespace App\Services\Translation;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use RuntimeException;
use Throwable;

class CursorTranslationAgent
{
    /**
     * @param  array<string, string>  $french
     * @return array<string, array<string, string>>
     */
    public function translate(array $french): array
    {
        if (! app()->runningUnitTests()) {
            set_time_limit((int) config('services.cursor.translation_timeout', 600) + 30);
        }

        $created = $this->start($french);

        try {
            $reply = $this->waitForReply($this->apiKey(), $created['agentId'], $created['runId']);
            $translations = TranslationReply::decode($reply);
        } catch (RuntimeException $exception) {
            $url = $created['url'] !== '' ? ' Agent : '.$created['url'] : '';

            throw new RuntimeException($exception->getMessage().$url, 0, $exception);
        }

        $this->deleteAgent($this->apiKey(), $created['agentId']);

        return $translations;
    }

    /**
     * @param  array<string, string>  $french
     * @return array{agentId: string, runId: string, url: string}
     */
    public function start(array $french): array
    {
        return $this->createAgent($this->apiKey(), $french);
    }

    /**
     * @return array<string, array<string, string>>|null
     */
    public function check(string $agentId, string $runId): ?array
    {
        $response = $this->http($this->apiKey())->get($this->url('/v1/agents/'.$agentId.'/runs/'.$runId));

        if (! $response->successful()) {
            $this->fail($response, 'Le suivi de l’agent de traduction a échoué.');
        }

        $status = $response->json('status');

        if (in_array($status, ['CREATING', 'RUNNING', 'WAITING_FOR_BACKGROUND_WORK'], true)) {
            return null;
        }

        if ($status !== 'FINISHED') {
            throw new RuntimeException('L’agent de traduction s’est arrêté ('.$status.').');
        }

        $result = $response->json('result');

        if (! is_string($result) || trim($result) === '') {
            throw new RuntimeException('L’agent de traduction a terminé sans réponse.');
        }

        return TranslationReply::decode($result);
    }

    public function forget(string $agentId): void
    {
        $this->deleteAgent($this->apiKey(), $agentId);
    }

    /**
     * @param  array<string, string>  $french
     * @return array{agentId: string, runId: string, url: string}
     */
    private function createAgent(string $apiKey, array $french): array
    {
        $response = $this->http($apiKey)
            ->post($this->url('/v1/agents'), [
                'name' => 'Traduction fiche',
                'prompt' => [
                    'text' => TranslationPrompt::text($french),
                ],
                'model' => [
                    'id' => (string) config('services.cursor.translation_model', 'gpt-5.4-nano'),
                ],
            ]);

        $agentId = $response->json('agent.id');
        $runId = $response->json('run.id');

        if (! $response->successful() || ! is_string($agentId) || $agentId === '' || ! is_string($runId) || $runId === '') {
            $this->fail($response, 'L’agent de traduction n’a pas pu démarrer.');
        }

        $url = $response->json('agent.url');

        return [
            'agentId' => $agentId,
            'runId' => $runId,
            'url' => is_string($url) ? $url : '',
        ];
    }

    private function waitForReply(string $apiKey, string $agentId, string $runId): string
    {
        $deadline = microtime(true) + (int) config('services.cursor.translation_timeout', 600);

        do {
            $response = $this->http($apiKey)->get($this->url('/v1/agents/'.$agentId.'/runs/'.$runId));

            if (! $response->successful()) {
                $this->fail($response, 'Le suivi de l’agent de traduction a échoué.');
            }

            $status = $response->json('status');

            if ($status === 'FINISHED') {
                $result = $response->json('result');

                if (! is_string($result) || trim($result) === '') {
                    throw new RuntimeException('L’agent de traduction a terminé sans réponse.');
                }

                return $result;
            }

            if (in_array($status, ['ERROR', 'CANCELLED', 'EXPIRED'], true)) {
                throw new RuntimeException('L’agent de traduction s’est arrêté ('.$status.').');
            }

            $this->pause();
        } while (microtime(true) < $deadline);

        throw new RuntimeException('L’agent de traduction a dépassé le temps d’attente.');
    }

    private function deleteAgent(string $apiKey, string $agentId): void
    {
        try {
            $response = $this->http($apiKey)
                ->timeout(5)
                ->delete($this->url('/v1/agents/'.$agentId));
        } catch (Throwable $exception) {
            report($exception);

            return;
        }

        if (! $response->successful()) {
            report(new RuntimeException('L’agent de traduction '.$agentId.' n’a pas pu être supprimé.'));
        }
    }

    private function pause(): void
    {
        $seconds = (int) config('services.cursor.poll_interval', 5);

        if ($seconds > 0) {
            sleep($seconds);
        }
    }

    private function apiKey(): string
    {
        $apiKey = config('services.cursor.api_key');

        if (! is_string($apiKey) || $apiKey === '') {
            throw new RuntimeException('Ajoute CURSOR_API_KEY dans le fichier .env pour lancer l’agent de traduction.');
        }

        return $apiKey;
    }

    private function http(string $apiKey): PendingRequest
    {
        return Http::withBasicAuth($apiKey, '')
            ->acceptJson()
            ->asJson()
            ->timeout(30);
    }

    private function url(string $path): string
    {
        return rtrim((string) config('services.cursor.base_url', 'https://api.cursor.com'), '/').$path;
    }

    private function fail(Response $response, string $fallback): never
    {
        $message = $response->json('message') ?? $response->json('error.message');

        if (! is_string($message) || trim($message) === '') {
            $message = $fallback.' (HTTP '.$response->status().')';
        }

        throw new RuntimeException($message);
    }
}
