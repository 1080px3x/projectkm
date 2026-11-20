<?php

namespace App\Filament\Resources\Comics\Schemas;

use App\Models\Comic;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ComicForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Detail Komik')
                    ->schema([
                        TextInput::make('title')
                            ->label('Judul')
                            ->required()
                            ->maxLength(150)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (?string $state, callable $set) => $set('slug', Str::slug((string) $state))),
                        TextInput::make('slug')
                            ->maxLength(150)
                            ->unique(Comic::class, 'slug', ignoreRecord: true)
                            ->helperText('Otomatis dari judul, bisa diubah.'),
                        Select::make('type')
                            ->options([
                                'manhwa' => 'Manhwa',
                                'manhua' => 'Manhua',
                                'manga' => 'Manga',
                                'project' => 'Project',
                            ])
                            ->required()
                            ->default('manhwa'),
                        TextInput::make('status')
                            ->required()
                            ->default('Ongoing')
                            ->maxLength(30),
                        TextInput::make('rating')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(10)
                            ->step(0.1)
                            ->default(0),
                        TextInput::make('genres')
                            ->label('Genre (pisah koma)')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Action, Fantasy'),
                        Textarea::make('synopsis')
                            ->required()
                            ->rows(4)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Section::make('Cover')
                    ->description('Pilih salah satu: upload file, URL, atau gradient. Kosongkan untuk mempertahankan cover saat edit.')
                    ->schema([
                        FileUpload::make('cover_file')
                            ->label('Upload Cover')
                            ->image()
                            ->directory('covers')
                            ->disk('public'),
                        TextInput::make('cover_url')
                            ->label('URL Cover')
                            ->url()
                            ->maxLength(500)
                            ->placeholder('https://...'),
                        TextInput::make('cover_gradient')
                            ->label('Gradient Cover')
                            ->maxLength(500)
                            ->placeholder('linear-gradient(135deg,#4c1d95,#be123c)'),
                    ])
                    ->columns(2),
            ]);
    }
}
