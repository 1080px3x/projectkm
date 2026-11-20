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
                    ->label('Cover')
                    ->height(60)
                    ->width(44)
                    ->checkFileExistence(false)
                    ->getStateUsing(fn ($record) => $record->coverUrl() ?? asset('storage/'.$record->cover)),
                TextColumn::make('title')
                    ->label('Judul')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('type')
                    ->badge()
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->color('success')
                    ->sortable(),
                TextColumn::make('rating')
                    ->numeric(decimalPlaces: 1)
                    ->sortable(),
                TextColumn::make('chapters_count')
                    ->label('Chapter')
                    ->counts('chapters')
                    ->badge()
                    ->color('info'),
                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('type')
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
