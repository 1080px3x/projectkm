<?php

namespace App\Filament\Widgets;

use App\Models\Chapter;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class RecentChapters extends TableWidget
{
    protected static ?int $sort = 5;

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->heading('Chapter Terbaru')
            ->query(fn (): Builder => Chapter::query()->with('comic')->withCount('images')->latest('released_at'))
            ->columns([
                TextColumn::make('comic.title')
                    ->label('Komik')
                    ->searchable(),
                TextColumn::make('number')
                    ->label('Chapter')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('title')
                    ->label('Judul')
                    ->placeholder('—')
                    ->limit(40),
                TextColumn::make('images_count')
                    ->label('Gambar')
                    ->badge(),
                TextColumn::make('released_at')
                    ->label('Rilis')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('released_at', 'desc')
            ->defaultPaginationPageOption(5)
            ->paginated([5, 10]);
    }
}
