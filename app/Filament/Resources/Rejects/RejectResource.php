<?php

namespace App\Filament\Resources\Rejects;

use App\Filament\Resources\Rejects\Pages\CreateReject;
use App\Filament\Resources\Rejects\Pages\EditReject;
use App\Filament\Resources\Rejects\Pages\ListRejects;
use App\Filament\Resources\Rejects\Schemas\RejectForm;
use App\Filament\Resources\Rejects\Tables\RejectsTable;
use App\Models\Reject;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class RejectResource extends Resource
{
    protected static ?string $model = Reject::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Reject';

    public static function form(Schema $schema): Schema
    {
        return RejectForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RejectsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListRejects::route('/'),
            'create' => CreateReject::route('/create'),
            'edit' => EditReject::route('/{record}/edit'),
        ];
    }
}
