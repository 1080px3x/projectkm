<?php

namespace App\Filament\Resources\Chapters\Pages;

use App\Filament\Resources\Chapters\ChapterResource;
use App\Support\ChapterImages;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditChapter extends EditRecord
{
    protected static string $resource = ChapterResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        return ChapterImages::chapterAttributes($data);
    }

    protected function afterSave(): void
    {
        ChapterImages::store($this->record, $this->form->getRawState());
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
