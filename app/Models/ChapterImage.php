<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class ChapterImage extends Model
{
    protected $fillable = ['chapter_id', 'image_path', 'order'];

    protected function casts(): array
    {
        return [
            'order' => 'integer',
        ];
    }

    public function chapter(): BelongsTo
    {
        return $this->belongsTo(Chapter::class);
    }

    public function url(): string
    {
        if (Str::startsWith($this->image_path, ['http://', 'https://'])) {
            return $this->image_path;
        }

        return asset('storage/'.ltrim($this->image_path, '/'));
    }
}
