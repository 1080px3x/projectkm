<?php

namespace App\Filament\Resources\Chapters\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ChaptersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('comic.title')
                    ->label('Komik')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('number')
                    ->label('Chapter')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('title')
                    ->searchable()
                    ->placeholder('—'),
                TextColumn::make('images_count')
                    ->label('Gambar')
                    ->counts('images')
                    ->badge(),
                TextColumn::make('released_at')
                    ->label('Rilis')
                    ->dateTime()
                    ->sortable(),
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
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('released_at', 'desc');
    }
}
