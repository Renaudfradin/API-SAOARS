<?php

namespace App\Filament\Translatable;

use Filament\Support\Contracts\TranslatableContentDriver;
use Illuminate\Database\Connection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

use function Filament\Support\generate_search_column_expression;

class SpatieTranslatableContentDriver implements TranslatableContentDriver
{
    public function __construct(protected string $activeLocale) {}

    public function isAttributeTranslatable(string $model, string $attribute): bool
    {
        $model = app($model);

        if (! method_exists($model, 'isTranslatableAttribute')) {
            return false;
        }

        return $model->isTranslatableAttribute($attribute);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function makeRecord(string $model, array $data): Model
    {
        $record = new $model;

        if (method_exists($record, 'setLocale')) {
            $record->setLocale($this->activeLocale);
        }

        $translatableAttributes = method_exists($record, 'getTranslatableAttributes')
            ? $record->getTranslatableAttributes()
            : [];

        $record->fill(Arr::except($data, $translatableAttributes));

        $this->setTranslations($record, Arr::only($data, $translatableAttributes));

        return $record;
    }

    public function setRecordLocale(Model $record): Model
    {
        if (! method_exists($record, 'setLocale')) {
            return $record;
        }

        return $record->setLocale($this->activeLocale);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function updateRecord(Model $record, array $data): Model
    {
        if (method_exists($record, 'setLocale')) {
            $record->setLocale($this->activeLocale);
        }

        $translatableAttributes = method_exists($record, 'getTranslatableAttributes')
            ? $record->getTranslatableAttributes()
            : [];

        $record->fill(Arr::except($data, $translatableAttributes));

        $this->setTranslations($record, Arr::only($data, $translatableAttributes));

        $record->save();

        return $record;
    }

    /**
     * @return array<string, mixed>
     */
    public function getRecordAttributesToArray(Model $record): array
    {
        $attributes = $record->attributesToArray();

        if (! method_exists($record, 'getTranslatableAttributes') || ! method_exists($record, 'getTranslation')) {
            return $attributes;
        }

        foreach ($record->getTranslatableAttributes() as $attribute) {
            $attributes[$attribute] = $this->translationForForm(
                $record->getTranslation($attribute, $this->activeLocale, useFallbackLocale: false),
            );
        }

        return $attributes;
    }

    /**
     * @param  array<string, mixed>  $translations
     */
    protected function setTranslations(Model $record, array $translations): void
    {
        if (! method_exists($record, 'setTranslation')) {
            return;
        }

        foreach ($translations as $key => $value) {
            if (is_array($value)) {
                continue;
            }

            $record->setTranslation($key, $this->activeLocale, $value ?? '');
        }
    }

    protected function translationForForm(mixed $value): mixed
    {
        if (is_string($value) || $value === null) {
            return $value;
        }

        if (is_scalar($value)) {
            return (string) $value;
        }

        return '';
    }

    public function applySearchConstraintToQuery(Builder $query, string $column, string $search, string $whereClause, ?bool $isSearchForcedCaseInsensitive = null): Builder
    {
        /** @var Connection $databaseConnection */
        $databaseConnection = $query->getConnection();

        $column = match ($databaseConnection->getDriverName()) {
            'pgsql' => "{$column}->>'{$this->activeLocale}'",
            default => "json_extract({$column}, \"$.{$this->activeLocale}\")",
        };

        return $query->{$whereClause}(
            generate_search_column_expression($column, $isSearchForcedCaseInsensitive, $databaseConnection),
            'like',
            (string) str($search)->wrap('%'),
        );
    }
}
