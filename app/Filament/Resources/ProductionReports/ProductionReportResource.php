<?php

namespace App\Filament\Resources\ProductionReports;

use App\Filament\Resources\ProductionReports\Pages\CreateProductionReport;
use App\Filament\Resources\ProductionReports\Pages\EditProductionReport;
use App\Filament\Resources\ProductionReports\Pages\ListProductionReports;
use App\Filament\Resources\ProductionReports\RelationManagers\DetailsRelationManager;
use App\Filament\Resources\ProductionReports\Schemas\ProductionReportForm;
use App\Filament\Resources\ProductionReports\Tables\ProductionReportsTable;
use App\Models\ProductionReport;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use UnitEnum;

class ProductionReportResource extends Resource
{
    protected static ?string $model = ProductionReport::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string |UnitEnum| null $navigationGroup = 'Production';
    
    protected static ?string $recordTitleAttribute = 'ProductionReport';

    public static function form(Schema $schema): Schema
    {
        return ProductionReportForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProductionReportsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
            DetailsRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListProductionReports::route('/'),
            'create' => CreateProductionReport::route('/create'),
            'edit' => EditProductionReport::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return Auth::user()?->hasAnyRole([
            'Super Admin',
            'Leader',
        ]) ?? false;
    }

    public static function canEdit($record): bool
    {
        $user = Auth::user();

        if ($user->hasRole('Super Admin')) {
            return true;
        }

        if ($user->hasRole('Leader')) {
            return $record->leader_id === $user->id;
        }

        if ($user->hasRole('Operator')) {
            return true;
        }

        return false;
    }

    public static function canDelete($record): bool
    {
        $user = Auth::user();

        if ($user->hasRole('Super Admin')) {
            return true;
        }

        if ($user->hasRole('Leader')) {
            return $record->leader_id === $user->id;
        }

        return false;
    }

    
}
