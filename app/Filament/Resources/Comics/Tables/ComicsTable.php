<?php

namespace App\Filament\Resources\Comics\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ComicsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('cover')
                    ->label('')
                    ->height(56)
                    ->width(40)
                    ->checkFileExistence(false)
                    ->getStateUsing(fn ($record) => $record->coverUrl()),
                TextColumn::make('title')
                    ->label('Judul')
                    ->description(fn ($record) => $record->genres)
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('type')
                    ->label('Tipe')
                    ->badge()
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state) => $state === 'Ongoing' ? 'success' : 'info'),
                TextColumn::make('chapters_count')
                    ->label('Chapter')
                    ->counts('chapters')
                    ->badge()
                    ->color('gray'),
                TextColumn::make('rating')
                    ->label('Rating')
                    ->numeric(decimalPlaces: 1)
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->label('Diperbarui')
                    ->since()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->label('Tipe')
                    ->options([
                        'manhwa' => 'Manhwa',
                        'manhua' => 'Manhua',
                        'manga' => 'Manga',
                        'project' => 'Project',
                    ]),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
