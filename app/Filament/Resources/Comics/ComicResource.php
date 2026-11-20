<?php

namespace App\Filament\Resources\Comics;

use App\Filament\Resources\Comics\Pages\CreateComic;
use App\Filament\Resources\Comics\Pages\EditComic;
use App\Filament\Resources\Comics\Pages\ListComics;
use App\Filament\Resources\Comics\Schemas\ComicForm;
use App\Filament\Resources\Comics\Tables\ComicsTable;
use App\Models\Comic;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class ComicResource extends Resource
{
    protected static ?string $model = Comic::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'title';

    protected static ?string $navigationLabel = 'Komik';

    protected static ?string $modelLabel = 'Komik';

    protected static ?string $pluralModelLabel = 'Komik';

    public static function form(Schema $schema): Schema
    {
        return ComicForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ComicsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\ChaptersRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListComics::route('/'),
            'create' => CreateComic::route('/create'),
            'edit' => EditComic::route('/{record}/edit'),
        ];
    }

    public static function uniqueSlug(string $value, ?int $ignoreId = null): string
    {
        $base = Str::slug($value) ?: 'komik';
        $slug = $base;
        $i = 1;

        while (Comic::where('slug', $slug)->when($ignoreId, fn ($q) => $q->whereKeyNot($ignoreId))->exists()) {
            $slug = $base.'-'.$i++;
        }

        return $slug;
    }

    public static function resolveCover(array $data, ?string $fallback): string
    {
        if ($file = trim((string) ($data['cover_file'] ?? ''))) {
            return $file;
        }

        if ($url = trim((string) ($data['cover_url'] ?? ''))) {
            return $url;
        }

        if ($gradient = trim((string) ($data['cover_gradient'] ?? ''))) {
            return $gradient;
        }

        return $fallback ?: 'linear-gradient(135deg,#4c1d95,#be123c)';
    }
}
