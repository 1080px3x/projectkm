<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Comic extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'slug', 'type', 'status', 'rating', 'cover', 'synopsis', 'genres',
    ];

    protected function casts(): array
    {
        return [
            'rating' => 'float',
        ];
    }

    public function chapters(): HasMany
    {
        return $this->hasMany(Chapter::class)->orderByDesc('number');
    }

    public function bookmarks(): HasMany
    {
        return $this->hasMany(Bookmark::class);
    }

    public function ratings(): HasMany
    {
        return $this->hasMany(Rating::class);
    }

    public function readingHistories(): HasMany
    {
        return $this->hasMany(ReadingHistory::class);
    }

    public function latestChapter(): ?Chapter
    {
        return $this->chapters->first();
    }

    public function genreList(): array
    {
        return array_map('trim', explode(',', $this->genres));
    }

    public function coverIsGradient(): bool
    {
        return str_contains($this->cover, 'gradient(');
    }

    public function coverUrl(): ?string
    {
        if ($this->coverIsGradient()) {
            return null;
        }

        if (Str::startsWith($this->cover, ['http://', 'https://'])) {
            return $this->cover;
        }

        return asset('storage/'.ltrim($this->cover, '/'));
    }

    public function coverStyle(): string
    {
        return $this->coverIsGradient() ? $this->cover : '';
    }

    public function scopeSearch($query, ?string $term)
    {
        return $term ? $query->where('title', 'like', '%'.$term.'%') : $query;
    }

    public function scopeOfType($query, ?string $type)
    {
        return $type ? $query->where('type', $type) : $query;
    }
}
