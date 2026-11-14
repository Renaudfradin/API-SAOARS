<?php

namespace App\Services\Translation;

class CatalogContentTranslator
{
    public function __construct(private CatalogTranslationManager $manager) {}

    /**
     * @param  array<string, mixed>  $french
     * @return array<string, array<string, string>>
     */
    public function translateAll(array $french): array
    {
        $source = $this->source($french);
        $translations = [];

        foreach ($this->manager->driver()->translate($source) as $locale => $translated) {
            if (! in_array($locale, TranslationPrompt::targets(), true) || ! is_array($translated)) {
                continue;
            }

            $translations[$locale] = $this->merge($source, $translated);
        }

        return $translations;
    }

    /**
     * @param  array<string, mixed>  $french
     * @return array{agentId: string, runId: string, url: string, ready?: array<string, array<string, string>>}
     */
    public function begin(array $french, ?string $provider = null): array
    {
        return $this->manager->driver($provider)->start($this->source($french));
    }

    /**
     * @param  array<string, mixed>  $french
     * @return array<string, array<string, string>>|null
     */
    public function collect(string $agentId, string $runId, array $french, ?string $provider = null): ?array
    {
        $driver = $this->manager->driver($provider);
        $raw = $driver->check($agentId, $runId);

        if ($raw === null) {
            return null;
        }

        $translations = $this->apply($french, $raw);

        app()->terminating(function () use ($driver, $agentId): void {
            $driver->forget($agentId);
        });

        return $translations;
    }

    /**
     * @param  array<string, mixed>  $french
     * @param  array<string, array<string, mixed>>  $rawTranslations
     * @return array<string, array<string, string>>
     */
    public function apply(array $french, array $rawTranslations): array
    {
        $source = $this->source($french);
        $translations = [];

        foreach ($rawTranslations as $locale => $translated) {
            if (! is_string($locale) || ! in_array($locale, TranslationPrompt::targets(), true) || ! is_array($translated)) {
                continue;
            }

            $fields = [];

            foreach ($translated as $key => $value) {
                if (! is_string($key)) {
                    continue;
                }

                $fields[$key] = is_string($value) ? $value : '';
            }

            $translations[$locale] = $this->merge($source, $fields);
        }

        return $translations;
    }

    /**
     * @param  array<string, mixed>  $french
     * @return array<string, string>
     */
    private function source(array $french): array
    {
        $source = [];

        foreach ($french as $key => $value) {
            if (! is_string($key)) {
                continue;
            }

            $source[$key] = is_string($value) ? $value : '';
        }

        return $source;
    }

    /**
     * @param  array<string, string>  $french
     * @param  array<string, string>  $translated
     * @return array<string, string>
     */
    private function merge(array $french, array $translated): array
    {
        $merged = [];

        foreach ($french as $key => $value) {
            if (trim($value) === '') {
                $merged[$key] = '';

                continue;
            }

            $next = $translated[$key] ?? '';
            $merged[$key] = trim($next) !== '' ? $next : $value;
        }

        return $merged;
    }
}
