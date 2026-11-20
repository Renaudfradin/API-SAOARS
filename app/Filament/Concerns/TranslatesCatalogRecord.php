<?php

namespace App\Filament\Concerns;

use App\Services\Translation\CatalogContentTranslator;
use App\Services\Translation\CatalogTranslationManager;
use App\Services\Translation\TranslationPrompt;
use App\Support\Locales;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Arr;
use Livewire\Attributes\Locked;
use Throwable;

trait TranslatesCatalogRecord
{
    /**
     * @var array<string, array<string, string>>
     */
    #[Locked]
    public array $otherLocaleData = [];

    public ?string $oldActiveLocale = null;

    #[Locked]
    public ?string $translationProvider = null;

    #[Locked]
    public ?string $translationAgentId = null;

    #[Locked]
    public ?string $translationRunId = null;

    #[Locked]
    public ?string $translationAgentUrl = null;

    #[Locked]
    public bool $translationProgressNotified = false;

    #[Locked]
    public bool $translationOnlyEmpty = false;

    /**
     * @var array<string, string>
     */
    #[Locked]
    public array $translationSource = [];

    public function translateCatalogAction(): Action
    {
        return Action::make('translateCatalog')
            ->label('Traduire')
            ->icon(Heroicon::OutlinedLanguage)
            ->color('gray')
            ->requiresConfirmation()
            ->modalHeading('Traduire depuis le français')
            ->modalDescription('Choisis le service. Le texte français lui est envoyé avec le même prompt. Reste sur cette page : le résultat s’affiche dès que la réponse arrive.')
            ->schema([
                Select::make('provider')
                    ->label('Service')
                    ->options(fn (): array => app(CatalogTranslationManager::class)->options())
                    ->default(fn (): string => (string) config('translation.provider', 'cursor'))
                    ->required()
                    ->native(false),
                Toggle::make('only_empty')
                    ->label('Champs vides seulement')
                    ->helperText('Ne remplit que les traductions encore vides. Sans cette option, les textes déjà enregistrés sont remplacés.')
                    ->default(false),
            ])
            ->modalSubmitActionLabel('Traduire')
            ->action(function (array $data): void {
                $provider = $data['provider'] ?? null;

                $this->translateCatalogFromFrench(
                    is_string($provider) ? $provider : null,
                    (bool) ($data['only_empty'] ?? false),
                );
            });
    }

    public function updatingActiveLocale(): void
    {
        $this->oldActiveLocale = $this->activeLocale;
    }

    public function updatedActiveLocale(): void
    {
        if (blank($this->oldActiveLocale) || $this->oldActiveLocale === $this->activeLocale) {
            return;
        }

        if (! in_array($this->activeLocale, Locales::ALL, true)) {
            $this->activeLocale = $this->oldActiveLocale;

            return;
        }

        $this->resetValidation();

        $this->otherLocaleData[$this->oldActiveLocale] = $this->currentLocaleData();

        $incoming = $this->otherLocaleData[$this->activeLocale] ?? null;
        unset($this->otherLocaleData[$this->activeLocale]);

        $this->form->fillPartially(
            $incoming ?? $this->blankTranslation(),
            $this->translatableAttributes(),
        );
    }

    public function translateCatalogFromFrench(?string $provider = null, bool $onlyEmpty = false): void
    {
        $french = $this->frenchSource();

        if (! is_array($french) || $this->translationIsEmpty($french)) {
            $this->notifyTranslation(
                'warning',
                'Texte français manquant',
                'Écris le texte en français avant de traduire.',
            );

            return;
        }

        if (filled($this->translationAgentId)) {
            $this->notifyTranslation(
                'warning',
                'Traduction déjà en cours',
                'Reste sur cette page. Le résultat s’affichera dès que le service aura répondu.',
            );
            $this->scheduleTranslationPoll();

            return;
        }

        if ($onlyEmpty && ! $this->hasEmptyTranslationTarget($french)) {
            $this->notifyTranslation(
                'warning',
                'Rien à traduire',
                'Tous les champs ont déjà une traduction.',
            );

            return;
        }

        $provider = $provider ?: (string) config('translation.provider', 'cursor');
        $this->translationOnlyEmpty = $onlyEmpty;

        try {
            $started = app(CatalogContentTranslator::class)->begin($french, $provider);
        } catch (Throwable $exception) {
            report($exception);
            $this->translationOnlyEmpty = false;

            $this->notifyTranslation('danger', 'Traduction échouée', $exception->getMessage());

            return;
        }

        $this->translationProvider = $provider;
        $this->translationSource = $french;
        $label = app(CatalogTranslationManager::class)->label($provider);

        if (is_array($started['ready'] ?? null)) {
            $this->showTranslations(app(CatalogContentTranslator::class)->apply($french, $started['ready']));
            $this->clearTranslationAgent();

            return;
        }

        $this->translationAgentId = $started['agentId'];
        $this->translationRunId = $started['runId'];
        $this->translationAgentUrl = $started['url'];

        $this->notifyTranslation(
            'info',
            'Traduction lancée',
            $label.' traduit la fiche. Reste sur cette page : le résultat s’affichera dans le formulaire.',
        );

        $this->scheduleTranslationPoll();
    }

    public function pollCatalogTranslation(): void
    {
        if (blank($this->translationAgentId) || blank($this->translationRunId)) {
            return;
        }

        try {
            $translations = app(CatalogContentTranslator::class)->collect(
                $this->translationAgentId,
                $this->translationRunId,
                $this->translationSource,
                $this->translationProvider,
            );
        } catch (ConnectionException $exception) {
            report($exception);

            if (! $this->translationProgressNotified) {
                $this->translationProgressNotified = true;
                $this->notifyTranslation(
                    'info',
                    'Traduction en cours',
                    'Le service met du temps à répondre. Reste sur cette page.',
                );
            }

            $this->scheduleTranslationPoll();

            return;
        } catch (Throwable $exception) {
            report($exception);
            $body = $exception->getMessage();

            if (filled($this->translationAgentUrl)) {
                $body .= ' '.$this->translationAgentUrl;
            }

            $this->clearTranslationAgent();

            $this->notifyTranslation('danger', 'Traduction échouée', $body);

            return;
        }

        if ($translations === null) {
            if (! $this->translationProgressNotified) {
                $this->translationProgressNotified = true;
                $this->notifyTranslation(
                    'info',
                    'Traduction en cours',
                    app(CatalogTranslationManager::class)->label($this->translationProvider).' travaille encore. Reste sur cette page.',
                );
            }

            $this->scheduleTranslationPoll();

            return;
        }

        $this->showTranslations($translations);
        $this->clearTranslationAgent();
    }

    protected function fillForm(): void
    {
        if (! $this instanceof EditRecord) {
            parent::fillForm();

            return;
        }

        $record = $this->getRecord();
        $data = $record->attributesToArray();
        $attributes = $this->translatableAttributes($record);

        foreach (Locales::ALL as $locale) {
            $localeData = [];

            foreach ($attributes as $attribute) {
                $localeData[$attribute] = $this->normalizeTranslationValue(
                    $record->getTranslation($attribute, $locale, false),
                );
            }

            if ($locale === $this->activeLocale) {
                $data = [
                    ...$data,
                    ...$localeData,
                ];

                continue;
            }

            $this->otherLocaleData[$locale] = $localeData;
        }

        $this->callHook('beforeFill');

        $data = $this->mutateFormDataBeforeFill($data);

        $this->form->fill($data);

        $this->callHook('afterFill');
    }

    /**
     * @param  array<string, mixed>  $data
     */
    protected function handleRecordCreation(array $data): Model
    {
        $record = $this->persistCatalogTranslations(new ($this->getModel()), $data, false);

        if ($parentRecord = $this->getParentRecord()) {
            return $this->associateRecordWithParent($record, $parentRecord);
        }

        $record->save();

        return $record;
    }

    /**
     * @param  array<string, mixed>  $data
     */
    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        return $this->persistCatalogTranslations($record, $data, true);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    protected function persistCatalogTranslations(Model $record, array $data, bool $save): Model
    {
        $attributes = $this->translatableAttributes($record);
        $record->fill(Arr::except($data, $attributes));

        $locales = [
            (string) $this->activeLocale => $this->normalizeLocaleData($data, $attributes),
        ];

        foreach ($this->otherLocaleData as $locale => $localeData) {
            if (! is_string($locale) || ! in_array($locale, Locales::ALL, true) || ! is_array($localeData)) {
                continue;
            }

            $locales[$locale] = $this->normalizeLocaleData($localeData, $attributes);
        }

        foreach ($locales as $locale => $localeData) {
            $this->writeLocale($record, $locale, $localeData);
        }

        if ($save) {
            $record->save();
        }

        return $record;
    }

    /**
     * @param  array<string, string>  $localeData
     */
    private function writeLocale(Model $record, string $locale, array $localeData): void
    {
        if (! method_exists($record, 'setTranslation')) {
            return;
        }

        if ($this->translationIsEmpty($localeData)) {
            if ($record->exists && method_exists($record, 'forgetTranslation')) {
                foreach (array_keys($localeData) as $attribute) {
                    $record->forgetTranslation($attribute, $locale);
                }
            }

            return;
        }

        foreach ($localeData as $attribute => $value) {
            $record->setTranslation($attribute, $locale, $value);
        }
    }

    /**
     * @param  array<string, array<string, string>>  $translations
     */
    private function showTranslations(array $translations): void
    {
        $attributes = $this->translatableAttributes();
        $done = [];
        $existing = $this->existingLocaleData();
        $previousLocale = $this->activeLocale;

        if ($this->translationOnlyEmpty) {
            $translations = $this->fillOnlyEmptyFields($translations, $existing);
        }

        $changedLocales = $this->localesWithNewText($translations, $existing);

        $this->otherLocaleData[Locales::DEFAULT] = $this->normalizeLocaleData($this->translationSource, $attributes);

        foreach (Locales::ALL as $locale) {
            if ($locale === Locales::DEFAULT) {
                continue;
            }

            $translated = $translations[$locale] ?? null;

            if (! is_array($translated)) {
                continue;
            }

            $this->otherLocaleData[$locale] = $this->normalizeLocaleData($translated, $attributes);
            $done[] = Locales::LABELS[$locale];
        }

        $visibleLocale = isset($translations['en']) ? 'en' : array_key_first($translations);

        if ($changedLocales !== []) {
            $visibleLocale = in_array($previousLocale, $changedLocales, true)
                ? $previousLocale
                : $changedLocales[0];
        }
        $visible = is_string($visibleLocale) ? ($this->otherLocaleData[$visibleLocale] ?? null) : null;

        if (is_string($visibleLocale) && is_array($visible)) {
            unset($this->otherLocaleData[$visibleLocale]);
            $this->activeLocale = $visibleLocale;
            $this->form->fillPartially($visible, $attributes);
        }

        $this->resetValidation();

        $missing = [];

        foreach (TranslationPrompt::targets() as $locale) {
            if (isset($translations[$locale])) {
                continue;
            }

            $missing[] = Locales::LABELS[$locale];
        }

        if ($done === []) {
            $this->notifyTranslation('danger', 'Traduction échouée', 'Le service n’a renvoyé aucune langue.');

            return;
        }

        $visibleLabel = Locales::LABELS[$this->activeLocale] ?? $this->activeLocale;
        $kept = $this->translationOnlyEmpty ? ' Les champs déjà remplis sont conservés.' : '';

        if ($missing === []) {
            $this->notifyTranslation(
                'success',
                'Traduction réussie',
                'La version '.$visibleLabel.' est affichée. Les autres langues sont dans le menu Langue : '.implode(', ', $done).'.'.$kept,
            );

            return;
        }

        $this->notifyTranslation(
            'warning',
            'Traduction partielle',
            'Prêtes : '.implode(', ', $done).'. À reprendre : '.implode(', ', $missing).'.'.$kept,
        );
    }

    private function notifyTranslation(string $status, string $title, ?string $body = null): void
    {
        $notification = Notification::make()->title($title);

        if (filled($body)) {
            $notification->body($body);
        }

        match ($status) {
            'success' => $notification
                ->success()
                ->icon(Heroicon::OutlinedCheckCircle)
                ->persistent(),
            'danger' => $notification
                ->danger()
                ->icon(Heroicon::OutlinedExclamationTriangle)
                ->persistent(),
            'warning' => $notification
                ->warning()
                ->icon(Heroicon::OutlinedExclamationCircle)
                ->persistent(),
            default => $notification
                ->info()
                ->icon(Heroicon::OutlinedLanguage)
                ->seconds(8),
        };

        $notification->send();
    }

    private function clearTranslationAgent(): void
    {
        $this->translationProvider = null;
        $this->translationAgentId = null;
        $this->translationRunId = null;
        $this->translationAgentUrl = null;
        $this->translationSource = [];
        $this->translationProgressNotified = false;
        $this->translationOnlyEmpty = false;
    }

    /**
     * @param  array<string, string>  $french
     */
    private function hasEmptyTranslationTarget(array $french): bool
    {
        foreach ($this->existingLocaleData() as $current) {
            foreach ($french as $attribute => $value) {
                if (! filled($value)) {
                    continue;
                }

                if (blank($current[$attribute] ?? null)) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * @param  array<string, array<string, string>>  $translations
     * @param  array<string, array<string, string>>  $existing
     * @return array<string, array<string, string>>
     */
    private function fillOnlyEmptyFields(array $translations, array $existing): array
    {
        foreach ($translations as $locale => $fields) {
            if (! is_array($fields)) {
                continue;
            }

            $current = $existing[$locale] ?? [];

            foreach ($fields as $attribute => $value) {
                if (! is_string($attribute)) {
                    continue;
                }

                if (filled($current[$attribute] ?? null)) {
                    $translations[$locale][$attribute] = $current[$attribute];
                }
            }
        }

        return $translations;
    }

    /**
     * @param  array<string, array<string, string>>  $translations
     * @param  array<string, array<string, string>>  $existing
     * @return list<string>
     */
    private function localesWithNewText(array $translations, array $existing): array
    {
        $changed = [];

        foreach (TranslationPrompt::targets() as $locale) {
            $fields = $translations[$locale] ?? null;

            if (! is_array($fields)) {
                continue;
            }

            $current = $existing[$locale] ?? [];

            foreach ($fields as $attribute => $value) {
                if (! is_string($attribute) || ! filled($value)) {
                    continue;
                }

                $previous = trim((string) ($current[$attribute] ?? ''));

                if ($previous === trim($value)) {
                    continue;
                }

                $changed[] = $locale;

                break;
            }
        }

        return $changed;
    }

    /**
     * @return array<string, string>|null
     */
    private function frenchSource(): ?array
    {
        $snapshot = $this->currentLocaleData();
        $french = $this->activeLocale === Locales::DEFAULT
            ? $snapshot
            : ($this->otherLocaleData[Locales::DEFAULT] ?? null);

        if (is_array($french) && ! $this->translationIsEmpty($french)) {
            return $this->normalizeLocaleData($french);
        }

        if ($this->activeLocale === Locales::DEFAULT || ! $this instanceof EditRecord) {
            return is_array($french) ? $this->normalizeLocaleData($french) : null;
        }

        $record = $this->getRecord();

        if (! method_exists($record, 'getTranslation')) {
            return is_array($french) ? $this->normalizeLocaleData($french) : null;
        }

        $recordFrench = [];

        foreach ($this->translatableAttributes($record) as $attribute) {
            $recordFrench[$attribute] = $this->normalizeTranslationValue(
                $record->getTranslation($attribute, Locales::DEFAULT, false),
            );
        }

        if (! $this->translationIsEmpty($recordFrench)) {
            return $recordFrench;
        }

        return is_array($french) ? $this->normalizeLocaleData($french) : null;
    }

    /**
     * @return array<string, array<string, string>>
     */
    private function existingLocaleData(): array
    {
        $existing = [];

        foreach (TranslationPrompt::targets() as $locale) {
            if ($locale === $this->activeLocale) {
                $existing[$locale] = $this->currentLocaleData();

                continue;
            }

            $stored = $this->otherLocaleData[$locale] ?? null;
            $existing[$locale] = is_array($stored)
                ? $this->normalizeLocaleData($stored)
                : $this->blankTranslation();
        }

        return $existing;
    }

    private function scheduleTranslationPoll(): void
    {
        if (app()->runningUnitTests()) {
            return;
        }

        $this->js('setTimeout(() => $wire.pollCatalogTranslation(), 5000)');
    }

    /**
     * @return array<string, string>
     */
    private function currentLocaleData(): array
    {
        $state = $this->form->getStateSnapshot();

        return $this->normalizeLocaleData(is_array($state) ? $state : []);
    }

    /**
     * @return list<string>
     */
    private function translatableAttributes(?Model $record = null): array
    {
        if ($record instanceof Model && method_exists($record, 'getTranslatableAttributes')) {
            return $record->getTranslatableAttributes();
        }

        if ($this instanceof EditRecord) {
            $record = $this->getRecord();

            if (method_exists($record, 'getTranslatableAttributes')) {
                return $record->getTranslatableAttributes();
            }
        }

        $model = $this->getModel();
        $instance = new $model;

        return method_exists($instance, 'getTranslatableAttributes')
            ? $instance->getTranslatableAttributes()
            : [];
    }

    /**
     * @param  array<string, mixed>  $data
     * @param  list<string>|null  $attributes
     * @return array<string, string>
     */
    private function normalizeLocaleData(array $data, ?array $attributes = null): array
    {
        $localeData = [];

        foreach ($attributes ?? $this->translatableAttributes() as $attribute) {
            $localeData[$attribute] = $this->normalizeTranslationValue($data[$attribute] ?? null);
        }

        return $localeData;
    }

    private function normalizeTranslationValue(mixed $value): string
    {
        if (is_string($value)) {
            return $value;
        }

        if (is_scalar($value)) {
            return (string) $value;
        }

        return '';
    }

    /**
     * @return array<string, string>
     */
    private function blankTranslation(): array
    {
        return $this->normalizeLocaleData([]);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    private function translationIsEmpty(array $data): bool
    {
        foreach ($this->normalizeLocaleData($data) as $value) {
            if (filled($value)) {
                return false;
            }
        }

        return true;
    }
}
