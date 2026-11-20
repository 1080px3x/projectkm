<?php

namespace App\Filament\Resources\Genres\Schemas;

use App\Models\Genre;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class GenreForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(60)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (?string $state, callable $set) => $set('slug', Str::slug((string) $state))),
                TextInput::make('slug')
                    ->required()
                    ->maxLength(60)
                    ->unique(Genre::class, 'slug', ignoreRecord: true),
            ]);
    }
}
