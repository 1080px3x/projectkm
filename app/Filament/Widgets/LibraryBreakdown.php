<?php

namespace App\Filament\Widgets;

use App\Models\Comic;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Cache;

class LibraryBreakdown extends ChartWidget
{
    protected static ?int $sort = 3;

    protected ?string $heading = 'Sebaran Koleksi';

    protected ?string $maxHeight = '250px';

    protected function getData(): array
    {
        $data = Cache::remember('dashboard_library_breakdown', 60, fn () => [
            'manhwa' => Comic::where('type', 'manhwa')->count(),
            'manhua' => Comic::where('type', 'manhua')->count(),
            'manga' => Comic::where('type', 'manga')->count(),
            'project' => Comic::where('type', 'project')->count(),
        ]);

        return [
            'datasets' => [
                [
                    'label' => 'Komik per Tipe',
                    'data' => array_values($data),
                    'backgroundColor' => [
                        'rgb(245, 158, 11)',
                        'rgb(234, 88, 12)',
                        'rgb(220, 38, 38)',
                        'rgb(120, 53, 15)',
                    ],
                    'borderWidth' => 0,
                ],
            ],
            'labels' => ['Manhwa', 'Manhua', 'Manga', 'Project'],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => ['position' => 'bottom'],
            ],
            'cutout' => '65%',
        ];
    }
}
