<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Comics\ComicResource;
use App\Models\Comic;
use Filament\Actions\Action;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class RecentComics extends TableWidget
{
    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->heading('Komik Terbaru')
            ->query(fn (): Builder => Comic::query()->withCount('chapters')->latest())
            ->columns([
                ImageColumn::make('cover')
                    ->label('')
                    ->height(48)
                    ->width(34)
                    ->checkFileExistence(false)
                    ->getStateUsing(fn (Comic $record) => $record->coverUrl()),
                TextColumn::make('title')
                    ->label('Judul')
                    ->searchable()
                    ->weight('bold'),
                TextColumn::make('type')
                    ->label('Tipe')
                    ->badge(),
                TextColumn::make('chapters_count')
                    ->label('Chapter')
                    ->badge()
                    ->color('info'),
                TextColumn::make('rating')
                    ->label('Rating')
                    ->numeric(decimalPlaces: 1),
                TextColumn::make('updated_at')
                    ->label('Diperbarui')
                    ->since(),
            ])
            ->recordActions([
                Action::make('edit')
                    ->label('Ubah')
                    ->icon('heroicon-m-pencil-square')
                    ->url(fn (Comic $record) => ComicResource::getUrl('edit', ['record' => $record])),
            ])
            ->defaultSort('created_at', 'desc')
            ->defaultPaginationPageOption(5)
            ->paginated([5, 10]);
    }
}
