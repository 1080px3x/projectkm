<?php

namespace App\Filament\Widgets;

use App\Models\Comic;
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
            ->query(fn (): Builder => Comic::query()->latest()->limit(5))
            ->columns([
                TextColumn::make('title')
                    ->label('Judul'),
                TextColumn::make('type')
                    ->badge(),
                TextColumn::make('rating')
                    ->numeric(decimalPlaces: 1),
                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime(),
            ]);
    }
}
