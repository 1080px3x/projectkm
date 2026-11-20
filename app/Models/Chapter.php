<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Chapter extends Model
{
    protected $fillable = ['comic_id', 'number', 'title', 'released_at'];

    protected function casts(): array
    {
        return [
            'released_at' => 'datetime',
        ];
    }

    public function comic(): BelongsTo
    {
        return $this->belongsTo(Comic::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ChapterImage::class)->orderBy('order');
    }

    public function label(): string
    {
        return 'Chapter '.$this->number;
    }

    public function timeAgo(): string
    {
        return str_replace(' yang lalu', '', $this->released_at->diffForHumans());
    }
}
