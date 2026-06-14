<?php

namespace App\Filament\Resources\Downtimes\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class DowntimeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('report_id')
                    ->label('Production Report')
                    ->relationship('report', 'id')
                    ->getOptionLabelFromRecordUsing(fn ($record) =>
                        $record->line?->name
                        . ' | ' . $record->shift?->name
                        .' | ' . $record->report_date
                    )
                    -> searchable()
                    ->getSearchResultsUsing(function (string $search) {
                        return \App\Models\ProductionReport::query()
                            ->with(['line', 'shift'])
                            ->whereHas('line', fn ($q) =>
                                $q->where('name', 'like', "%{$search}%")
                            )
                            ->orWhereHas('shift', fn ($q) =>
                                $q->where('name', 'like', "%{$search}%")
                            )
                            ->orWhere('report_date', 'like', "%{$search}%")
                            ->limit(50)
                            ->get()
                            ->mapWithKeys(fn ($record) => [
                                $record->id => $record->line?->name
                                    . ' | ' . $record->shift?->name
                                    . ' | ' . $record->report_date
                            ])
                            ->toArray();
                    })
                    ->preload()
                    ->required(),
                Select::make('machine_id')
                    ->label('Machine')
                    ->relationship('machine', 'machine_name')
                    ->searchable()
                    ->preload()
                    ->required(),
                DateTimePicker::make('start_time')
                    ->required(),
                DateTimePicker::make('end_time')
                    ->required(),
                TextInput::make('duration_minutes')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('reason')
                    ->required(),
            ]);
    }
}
