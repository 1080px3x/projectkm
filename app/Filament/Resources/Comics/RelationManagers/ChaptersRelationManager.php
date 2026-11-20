<?php

namespace App\Filament\Resources\Comics\RelationManagers;

use App\Support\ChapterImages;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ChaptersRelationManager extends RelationManager
{
    protected static string $relationship = 'chapters';

    protected static ?string $title = 'Chapter';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('number')
                    ->label('Nomor')
                    ->numeric()
                    ->required()
                    ->minValue(1)
                    ->default(fn () => (int) $this->getOwnerRecord()->chapters()->max('number') + 1),
                TextInput::make('title')
                    ->label('Judul')
                    ->maxLength(150),
                DateTimePicker::make('released_at')
                    ->label('Tanggal rilis')
                    ->required()
                    ->default(now()),
                FileUpload::make('images')
                    ->label('Upload gambar halaman')
                    ->image()
                    ->multiple()
                    ->reorderable()
                    ->appendFiles()
                    ->directory('chapters')
                    ->disk('public')
                    ->helperText('Pilih banyak file sekaligus.')
                    ->columnSpanFull(),
                Textarea::make('image_urls')
                    ->label('Atau tempel URL gambar')
                    ->rows(5)
                    ->placeholder("https://contoh.com/01.jpg\nhttps://contoh.com/02.jpg")
                    ->helperText('Satu URL per baris.')
                    ->columnSpanFull(),
            ])
            ->columns(2);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('number')
                    ->label('Chapter')
                    ->sortable(),
                TextColumn::make('title')
                    ->searchable()
                    ->placeholder('—'),
                TextColumn::make('images_count')
                    ->label('Halaman')
                    ->counts('images')
                    ->badge(),
                TextColumn::make('released_at')
                    ->label('Rilis')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('number', 'desc')
            ->headerActions([
                CreateAction::make()
                    ->using(function (array $data): void {
                        $chapter = $this->getOwnerRecord()->chapters()->create(
                            ChapterImages::chapterAttributes($data)
                        );

                        ChapterImages::store($chapter, $data);
                    }),
            ])
            ->recordActions([
                EditAction::make()
                    ->using(function ($record, array $data): void {
                        $record->update(ChapterImages::chapterAttributes($data));

                        ChapterImages::store($record, $data);
                    }),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
