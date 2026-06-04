<?php

namespace App\Filament\Resources\ProductionReports\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ProductionReportsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('line_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('part_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('shift_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('leader_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('report_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('total_target')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('total_actual')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('achievement')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('status')
                    ->badge(),
                TextColumn::make('approved_by')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('approved_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
