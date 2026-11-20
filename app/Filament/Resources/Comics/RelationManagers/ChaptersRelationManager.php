<?php

namespace App\Filament\Resources\Comics\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ChaptersRelationManager extends RelationManager
{
    protected static string $relationship = 'chapters';

    protected static ?string $title = 'Chapter';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('number')
                    ->numeric()
                    ->required()
                    ->minValue(1)
                    ->default(fn () => (int) $this->getOwnerRecord()->chapters()->max('number') + 1),
                TextInput::make('title')
                    ->maxLength(150),
                DateTimePicker::make('released_at')
                    ->required()
                    ->default(now()),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('number')
                    ->label('Chapter')
                    ->sortable(),
                TextColumn::make('title')
                    ->searchable()
                    ->placeholder('—'),
                TextColumn::make('images_count')
                    ->label('Gambar')
                    ->counts('images')
                    ->badge(),
                TextColumn::make('released_at')
                    ->label('Rilis')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('number', 'desc')
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
