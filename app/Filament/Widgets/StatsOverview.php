<?php

namespace App\Filament\Widgets;

use App\Models\Chapter;
use App\Models\Comic;
use App\Models\Genre;
use App\Models\Thread;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        return [
            Stat::make('Total Komik', Comic::count())
                ->description('Semua judul')
                ->descriptionIcon('heroicon-m-rectangle-stack')
                ->color('primary'),
            Stat::make('Total Chapter', Chapter::count())
                ->description('Semua chapter')
                ->descriptionIcon('heroicon-m-book-open')
                ->color('success'),
            Stat::make('Pengguna', User::count())
                ->description('Akun terdaftar')
                ->descriptionIcon('heroicon-m-users')
                ->color('warning'),
            Stat::make('Genre', Genre::count())
                ->description('Kategori')
                ->descriptionIcon('heroicon-m-tag')
                ->color('info'),
            Stat::make('Thread', Thread::count())
                ->description('Diskusi komunitas')
                ->descriptionIcon('heroicon-m-chat-bubble-left-right')
                ->color('gray'),
        ];
    }
}
