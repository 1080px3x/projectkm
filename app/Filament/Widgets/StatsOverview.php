<?php

namespace App\Filament\Widgets;

use App\Models\Chapter;
use App\Models\Comic;
use App\Models\Genre;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        return [
            Stat::make('Komik', Comic::count())
                ->description('Total judul')
                ->descriptionIcon('heroicon-m-rectangle-stack')
                ->color('primary'),
            Stat::make('Chapter', Chapter::count())
                ->description('Total chapter')
                ->descriptionIcon('heroicon-m-book-open')
                ->color('success'),
            Stat::make('Pengguna', User::count())
                ->description('Akun terdaftar')
                ->descriptionIcon('heroicon-m-users')
                ->color('warning'),
            Stat::make('Genre', Genre::count())
                ->description('Kategori')
                ->descriptionIcon('heroicon-m-tag')
                ->color('gray'),
        ];
    }
}
