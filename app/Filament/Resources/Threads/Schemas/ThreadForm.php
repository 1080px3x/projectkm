<?php

namespace App\Filament\Resources\Threads\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ThreadForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required()
                    ->maxLength(150),
                TextInput::make('author_name')
                    ->label('Nama Penulis')
                    ->required()
                    ->maxLength(60),
                Textarea::make('body')
                    ->required()
                    ->rows(5)
                    ->maxLength(2000)
                    ->columnSpanFull(),
            ])
            ->columns(2);
    }
}
