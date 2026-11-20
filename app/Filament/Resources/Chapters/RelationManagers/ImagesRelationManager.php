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
use Filament\Schemas\Schema;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class ImagesRelationManager extends RelationManager
{
    protected static string $relationship = 'images';

    protected static ?string $title = 'Halaman';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                FileUpload::make('image_path')
                    ->label('Upload gambar')
                    ->image()
                    ->directory('chapters')
                    ->disk('public')
                    ->helperText('Atau isi URL di bawah.'),
                TextInput::make('image_url')
                    ->label('URL gambar')
                    ->url()
                    ->placeholder('https://contoh.com/01.jpg'),
                TextInput::make('order')
                    ->label('Urutan')
                    ->numeric()
                    ->default(fn () => (int) $this->getOwnerRecord()->images()->max('order') + 1),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image_path')
                    ->label('Gambar')
                    ->height(72)
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
                    ->label('Tambah Halaman')
                    ->icon('heroicon-m-plus')
                    ->using(function (array $data) {
                        if ($url = trim((string) ($data['image_url'] ?? ''))) {
                            $data['image_path'] = $url;
                        }

                        unset($data['image_url']);

                        return $this->getOwnerRecord()->images()->create($data);
                    }),
                Action::make('bulkUrls')
                    ->label('Tempel Banyak URL')
                    ->icon('heroicon-m-queue-list')
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
                            ->title($urls->count().' halaman ditambahkan')
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
