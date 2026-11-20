<?php

namespace App\Support;

use App\Models\Chapter;
use Illuminate\Support\Arr;

class ChapterImages
{
    public static function store(Chapter $chapter, array $data): void
    {
        $order = (int) $chapter->images()->max('order');

        foreach (array_filter((array) ($data['images'] ?? []), 'is_string') as $path) {
            if ($path !== '') {
                $chapter->images()->create(['image_path' => $path, 'order' => ++$order]);
            }
        }

        $urls = collect(preg_split('/\r\n|\r|\n/', (string) ($data['image_urls'] ?? '')))
            ->map(fn ($url) => trim($url))
            ->filter(fn ($url) => filter_var($url, FILTER_VALIDATE_URL));

        foreach ($urls as $url) {
            $chapter->images()->create(['image_path' => $url, 'order' => ++$order]);
        }
    }

    public static function chapterAttributes(array $data): array
    {
        return Arr::only($data, ['comic_id', 'number', 'title', 'released_at']);
    }
}
