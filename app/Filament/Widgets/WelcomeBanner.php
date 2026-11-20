<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Comics\ComicResource;
use App\Models\Chapter;
use App\Models\Comic;
use Filament\Widgets\Widget;

class WelcomeBanner extends Widget
{
    protected static ?int $sort = 0;

    protected int|string|array $columnSpan = 'full';

    protected string $view = 'filament.widgets.welcome-banner';

    protected function getViewData(): array
    {
        return [
            'date' => now()->translatedFormat('l, d F Y'),
            'userName' => auth()->user()?->name ?? 'Admin',
            'comicsToday' => Comic::whereDate('created_at', today())->count(),
            'chaptersToday' => Chapter::whereDate('released_at', today())->count(),
            'createUrl' => ComicResource::getUrl('create'),
        ];
    }
}
