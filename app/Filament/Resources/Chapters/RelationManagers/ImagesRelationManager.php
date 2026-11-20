<?php

namespace App\Filament\Resources\Chapters\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ImagesRelationManager extends RelationManager
{
    protected static string $relationship = 'images';

    protected static ?string $title = 'Gambar Chapter';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                FileUpload::make('image_path')
                    ->label('Gambar')
                    ->image()
                    ->directory('chapters')
                    ->disk('public')
                    ->required(),
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
                    ->height(80),
                TextColumn::make('order')
                    ->label('Urutan')
                    ->sortable(),
            ])
            ->defaultSort('order')
            ->reorderable('order')
            ->headerActions([
                CreateAction::make(),
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
