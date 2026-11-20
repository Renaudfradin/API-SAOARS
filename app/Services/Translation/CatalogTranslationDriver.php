<?php

namespace App\Services\Translation;

interface CatalogTranslationDriver
{
    public function label(): string;

    /**
     * @param  array<string, string>  $french
     * @return array<string, array<string, string>>
     */
    public function translate(array $french): array;

    /**
     * @param  array<string, string>  $french
     * @return array{agentId: string, runId: string, url: string, ready?: array<string, array<string, string>>}
     */
    public function start(array $french): array;

    /**
     * @return array<string, array<string, string>>|null
     */
    public function check(string $agentId, string $runId): ?array;

    public function forget(string $agentId): void;
}
