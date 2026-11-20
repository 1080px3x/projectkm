<?php

namespace App\Filament\Widgets;

use App\Models\Comic;
use Filament\Widgets\Widget;

class TopComics extends Widget
{
    protected static ?int $sort = 4;

    protected string $view = 'filament.widgets.top-comics';

    protected function getViewData(): array
    {
        return [
            'topComics' => Comic::where('rating', '>', 0)
                ->orderByDesc('rating')
                ->limit(5)
                ->get(['id', 'title', 'rating', 'slug']),
        ];
    }
}
