<?php

namespace App\Filament\Resources\Downtimes;

use App\Filament\Resources\Downtimes\Pages\CreateDowntime;
use App\Filament\Resources\Downtimes\Pages\EditDowntime;
use App\Filament\Resources\Downtimes\Pages\ListDowntimes;
use App\Filament\Resources\Downtimes\Schemas\DowntimeForm;
use App\Filament\Resources\Downtimes\Tables\DowntimesTable;
use App\Models\Downtime;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class DowntimeResource extends Resource
{
    protected static ?string $model = Downtime::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string |UnitEnum| null $navigationGroup = 'Production';

    protected static ?string $recordTitleAttribute = 'Downtime';

    public static function form(Schema $schema): Schema
    {
        return DowntimeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DowntimesTable::configure($table);
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
            'index' => ListDowntimes::route('/'),
            'create' => CreateDowntime::route('/create'),
            'edit' => EditDowntime::route('/{record}/edit'),
        ];
    }
}
