<?php

namespace App\Support;

use App\Models\Bookmark;

class Bookmarks
{
    protected static function sessionId(): string
    {
        return session()->getId();
    }

    public static function has(int $comicId): bool
    {
        $user = auth()->id();

        return Bookmark::where('comic_id', $comicId)
            ->where(function ($q) use ($user) {
                $q->where('user_id', $user)->orWhere('session_id', self::sessionId());
            })
            ->exists();
    }

    public static function toggle(int $comicId): bool
    {
        $existing = Bookmark::where('comic_id', $comicId)
            ->where(function ($q) {
                if ($user = auth()->id()) {
                    $q->where('user_id', $user);
                } else {
                    $q->where('session_id', self::sessionId());
                }
            })
            ->first();

        if ($existing) {
            $existing->delete();

            return false;
        }

        Bookmark::create([
            'user_id' => auth()->id(),
            'session_id' => auth()->check() ? null : self::sessionId(),
            'comic_id' => $comicId,
        ]);

        return true;
    }

    public static function query()
    {
        return Bookmark::query()->where(function ($q) {
            if ($user = auth()->id()) {
                $q->where('user_id', $user);
            } else {
                $q->where('session_id', self::sessionId());
            }
        });
    }
}
