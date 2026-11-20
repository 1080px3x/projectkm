<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Bookmark extends Model
{
    protected $fillable = ['user_id', 'session_id', 'comic_id'];

    public function comic(): BelongsTo
    {
        return $this->belongsTo(Comic::class);
    }
}
