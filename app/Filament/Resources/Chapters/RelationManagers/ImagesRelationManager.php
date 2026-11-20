<?php

namespace App\Filament\Resources\Chapters\RelationManagers;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class ImagesRelationManager extends RelationManager
{
    protected static string $relationship = 'images';

    protected static ?string $title = 'Gambar Chapter';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                FileUpload::make('image_path')
                    ->label('Upload Gambar')
                    ->image()
                    ->directory('chapters')
                    ->disk('public')
                    ->helperText('Kosongkan jika memakai URL gambar.')
                    ->required(fn (Get $get) => blank($get('image_url'))),
                TextInput::make('image_url')
                    ->label('URL Gambar')
                    ->url()
                    ->placeholder('https://...')
                    ->helperText('Diutamakan jika diisi.')
                    ->required(fn (Get $get) => blank($get('image_path'))),
                TextInput::make('order')
                    ->numeric()
                    ->default(fn () => (int) $this->getOwnerRecord()->images()->max('order') + 1),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image_path')
                    ->label('Preview')
                    ->height(80)
                    ->getStateUsing(fn ($record) => $record->url()),
                TextColumn::make('source')
                    ->label('Sumber')
                    ->badge()
                    ->state(fn ($record) => Str::startsWith($record->image_path, ['http://', 'https://']) ? 'URL' : 'Upload')
                    ->color(fn (string $state) => $state === 'URL' ? 'info' : 'gray'),
                TextColumn::make('order')
                    ->label('Urutan')
                    ->sortable(),
            ])
            ->defaultSort('order')
            ->reorderable('order')
            ->headerActions([
                CreateAction::make()
                    ->using(function (array $data) {
                        if ($url = trim((string) ($data['image_url'] ?? ''))) {
                            $data['image_path'] = $url;
                        }

                        unset($data['image_url']);

                        return $this->getOwnerRecord()->images()->create($data);
                    }),
                Action::make('bulkUrls')
                    ->label('Tambah dari Banyak URL')
                    ->icon('heroicon-m-link')
                    ->schema([
                        Textarea::make('urls')
                            ->label('Satu URL per baris')
                            ->rows(8)
                            ->required()
                            ->placeholder("https://contoh.com/01.jpg\nhttps://contoh.com/02.jpg"),
                    ])
                    ->action(function (array $data) {
                        $urls = collect(preg_split('/\r\n|\r|\n/', (string) $data['urls']))
                            ->map(fn ($url) => trim($url))
                            ->filter(fn ($url) => filter_var($url, FILTER_VALIDATE_URL))
                            ->values();

                        $order = (int) $this->getOwnerRecord()->images()->max('order');

                        foreach ($urls as $url) {
                            $this->getOwnerRecord()->images()->create([
                                'image_path' => $url,
                                'order' => ++$order,
                            ]);
                        }

                        Notification::make()
                            ->success()
                            ->title($urls->count().' gambar ditambahkan')
                            ->send();
                    }),
            ])
            ->recordActions([
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
