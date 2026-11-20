<?php

namespace App\Filament\Resources\Comics\Pages;

use App\Filament\Resources\Comics\ComicResource;
use Filament\Resources\Pages\CreateRecord;

class CreateComic extends CreateRecord
{
    protected static string $resource = ComicResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['slug'] = ComicResource::uniqueSlug($data['slug'] ?? $data['title']);
        $data['cover'] = ComicResource::resolveCover($data, null);

        return collect($data)->except(['cover_file', 'cover_url', 'cover_gradient'])->all();
    }
}
