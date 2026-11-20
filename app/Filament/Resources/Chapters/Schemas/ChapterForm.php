<?php

namespace App\Filament\Resources\Chapters\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
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
                    ->label('Nomor')
                    ->numeric()
                    ->required()
                    ->minValue(1),
                TextInput::make('title')
                    ->label('Judul')
                    ->maxLength(150),
                DateTimePicker::make('released_at')
                    ->label('Tanggal rilis')
                    ->required()
                    ->default(now()),
                FileUpload::make('images')
                    ->label('Upload gambar halaman')
                    ->image()
                    ->multiple()
                    ->reorderable()
                    ->appendFiles()
                    ->directory('chapters')
                    ->disk('public')
                    ->helperText('Pilih banyak file sekaligus.')
                    ->columnSpanFull(),
                Textarea::make('image_urls')
                    ->label('Atau tempel URL gambar')
                    ->rows(5)
                    ->placeholder("https://contoh.com/01.jpg\nhttps://contoh.com/02.jpg")
                    ->helperText('Satu URL per baris.')
                    ->columnSpanFull(),
            ])
            ->columns(2);
    }
}
