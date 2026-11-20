<?php

namespace App\Filament\Resources\Chapters\Pages;

use App\Filament\Resources\Chapters\ChapterResource;
use App\Support\ChapterImages;
use Filament\Resources\Pages\CreateRecord;

class CreateChapter extends CreateRecord
{
    protected static string $resource = ChapterResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return ChapterImages::chapterAttributes($data);
    }

    protected function afterCreate(): void
    {
        ChapterImages::store($this->record, $this->form->getRawState());
    }
}
