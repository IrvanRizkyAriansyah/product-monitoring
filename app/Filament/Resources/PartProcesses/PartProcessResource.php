<?php

namespace App\Filament\Resources\PartProcesses;

use App\Filament\Resources\PartProcesses\Pages\CreatePartProcess;
use App\Filament\Resources\PartProcesses\Pages\EditPartProcess;
use App\Filament\Resources\PartProcesses\Pages\ListPartProcesses;
use App\Filament\Resources\PartProcesses\Schemas\PartProcessForm;
use App\Filament\Resources\PartProcesses\Tables\PartProcessesTable;
use App\Models\PartProcess;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class PartProcessResource extends Resource
{
    protected static ?string $model = PartProcess::class;

    protected static string |UnitEnum| null $navigationGroup = 'Master Data';
    
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'PartProcess';

    public static function form(Schema $schema): Schema
    {
        return PartProcessForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PartProcessesTable::configure($table);
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
            'index' => ListPartProcesses::route('/'),
            'create' => CreatePartProcess::route('/create'),
            'edit' => EditPartProcess::route('/{record}/edit'),
        ];
    }
}
