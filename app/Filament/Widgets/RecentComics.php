<?php

namespace App\Filament\Widgets;

use App\Models\Comic;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class RecentComics extends TableWidget
{
    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 'full';

    protected static ?string $heading = 'Baru Ditambahkan';

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => Comic::query()->latest())
            ->columns([
                ImageColumn::make('cover')
                    ->label('')
                    ->height(40)
                    ->width(30)
                    ->checkFileExistence(false)
                    ->getStateUsing(fn ($record) => $record->coverUrl()),
                TextColumn::make('title')
                    ->label('Judul')
                    ->description(fn (Comic $record): string => $record->slug)
                    ->limit(35),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Ongoing' => 'success',
                        'Completed' => 'info',
                        default => 'gray',
                    }),
                TextColumn::make('rating')
                    ->numeric(decimalPlaces: 1),
                TextColumn::make('created_at')
                    ->label('Ditambah')
                    ->since()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->defaultPaginationPageOption(5)
            ->paginated([5, 10]);
    }
}
