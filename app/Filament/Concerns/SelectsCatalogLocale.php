<?php

namespace App\Filament\Concerns;

use App\Filament\Translatable\SpatieTranslatableContentDriver;
use App\Support\Locales;
use Filament\Support\Contracts\TranslatableContentDriver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

trait SelectsCatalogLocale
{
    public ?string $activeLocale = Locales::DEFAULT;

    public function getActiveSchemaLocale(): ?string
    {
        return $this->activeLocale;
    }

    public function getActiveTableLocale(): ?string
    {
        return $this->activeLocale;
    }

    /**
     * @return class-string<TranslatableContentDriver>|null
     */
    public function getFilamentTranslatableContentDriver(): ?string
    {
        return SpatieTranslatableContentDriver::class;
    }

    public function updatedActiveLocale(): void
    {
        if (! method_exists($this, 'form') || ! method_exists($this, 'getRecord')) {
            return;
        }

        $record = $this->getRecord();

        if (! $record instanceof Model || ! $record->exists || ! method_exists($record, 'getTranslatableAttributes')) {
            return;
        }

        $attributes = $this->localizedRecordAttributes($record);
        $paths = $record->getTranslatableAttributes();

        $this->form->fillPartially(Arr::only($attributes, $paths), $paths);
    }

    protected function fillForm(): void
    {
        if (! method_exists($this, 'form')) {
            return;
        }

        $record = method_exists($this, 'getRecord') ? $this->getRecord() : null;

        if (! $record instanceof Model || ! $record->exists) {
            $this->callHook('beforeFill');
            $this->form->fill();
            $this->callHook('afterFill');

            return;
        }

        $this->callHook('beforeFill');

        $data = $this->localizedRecordAttributes($record);

        if (method_exists($this, 'mutateFormDataBeforeFill')) {
            $data = $this->mutateFormDataBeforeFill($data);
        }

        $this->form->fill($data);

        $this->callHook('afterFill');
    }

    /**
     * @param  array<string>  $statePaths
     */
    public function refreshFormData(array $statePaths): void
    {
        if (! method_exists($this, 'form') || ! method_exists($this, 'getRecord')) {
            return;
        }

        $record = $this->getRecord();

        if (! $record instanceof Model || ! $record->exists) {
            return;
        }

        $data = $this->localizedRecordAttributes($record);

        if (method_exists($this, 'mutateFormDataBeforeFill')) {
            $data = $this->mutateFormDataBeforeFill($data);
        }

        $this->form->fillPartially($data, $statePaths);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $driver = $this->makeFilamentTranslatableContentDriver();

        if ($driver && method_exists($record, 'isTranslatableAttribute')) {
            return $driver->updateRecord($record, $data);
        }

        $record->update($data);

        return $record;
    }

    /**
     * @param  array<string, mixed>  $data
     */
    protected function handleRecordCreation(array $data): Model
    {
        $modelClass = $this->getModel();
        $driver = $this->makeFilamentTranslatableContentDriver();

        $record = ($driver && method_exists($modelClass, 'isTranslatableAttribute'))
            ? $driver->makeRecord($modelClass, $data)
            : new $modelClass($data);

        if ($parentRecord = $this->getParentRecord()) {
            return $this->associateRecordWithParent($record, $parentRecord);
        }

        $record->save();

        return $record;
    }

    /**
     * @return array<string, mixed>
     */
    protected function localizedRecordAttributes(Model $record): array
    {
        $driver = $this->makeFilamentTranslatableContentDriver();

        if (! $driver || ! method_exists($record, 'getTranslatableAttributes')) {
            return $record->attributesToArray();
        }

        $driver->setRecordLocale($record);

        return $driver->getRecordAttributesToArray($record);
    }
}
