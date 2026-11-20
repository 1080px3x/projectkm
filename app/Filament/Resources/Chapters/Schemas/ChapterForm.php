<?php

namespace App\Filament\Resources\Chapters\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ChapterForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('comic_id')
                    ->label('Komik')
                    ->relationship('comic', 'title')
                    ->searchable()
                    ->preload()
                    ->required(),
                TextInput::make('number')
                    ->numeric()
                    ->required()
                    ->minValue(1),
                TextInput::make('title')
                    ->maxLength(150),
                DateTimePicker::make('released_at')
                    ->required()
                    ->default(now()),
            ])
            ->columns(2);
    }
}
