<?php

namespace App\Filament\Resources\Chapters\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ChaptersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('cover')
                    ->label('')
                    ->height(40)
                    ->width(28)
                    ->checkFileExistence(false)
                    ->getStateUsing(fn ($record) => optional($record->comic)->coverUrl()),
                TextColumn::make('comic.title')
                    ->label('Komik')
                    ->searchable()
                    ->weight('bold'),
                TextColumn::make('number')
                    ->label('Chapter')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('title')
                    ->label('Judul')
                    ->placeholder('—')
                    ->limit(40),
                TextColumn::make('images_count')
                    ->label('Halaman')
                    ->counts('images')
                    ->badge(),
            ])
            ->filters([
                SelectFilter::make('comic_id')
                    ->label('Komik')
                    ->relationship('comic', 'title')
                    ->searchable()
                    ->preload(),
            ])
            ->recordActions([
                EditAction::make(),
                Action::make('view')
                    ->label('Lihat di situs')
                    ->url(fn ($record) => route('chapters.show', [$record->comic->slug, $record->number])),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('released_at', 'desc');
    }
}
