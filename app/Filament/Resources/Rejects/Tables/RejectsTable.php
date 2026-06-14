<?php

namespace App\Filament\Resources\Rejects\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class RejectsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('report')
                    ->label('Production Report')
                    ->formatStateUsing(fn ($record) =>
                        $record->report?->line?->name
                        . ' | ' . $record->report?->shift?->name
                        . ' | ' . $record->report?->report_date
                    )
                    ->sortable(),
                TextColumn::make('rejectType.name')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('qty')
                    ->numeric()
                    ->sortable(),
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
