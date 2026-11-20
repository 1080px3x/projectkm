<?php

namespace App\Filament\Resources\Comics\Pages;

use App\Filament\Resources\Comics\ComicResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditComic extends EditRecord
{
    protected static string $resource = ComicResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['cover_url'] = str_starts_with((string) $data['cover'], 'http') ? $data['cover'] : null;
        $data['cover_gradient'] = str_contains((string) $data['cover'], 'gradient(') ? $data['cover'] : null;

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['slug'] = ComicResource::uniqueSlug($data['slug'] ?? $data['title'], $this->record->id);
        $data['cover'] = ComicResource::resolveCover($data, $this->record->cover);

        return collect($data)->except(['cover_file', 'cover_url', 'cover_gradient'])->all();
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
