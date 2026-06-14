<?php

namespace App\Filament\Resources\Rejects\Schemas;

use App\Models\RejectType;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class RejectForm
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
                        .' | ' . $record->part?->part_name
                    )
                    -> searchable()
                    ->getSearchResultsUsing(function (string $search) {
                        return \App\Models\ProductionReport::query()
                            ->with(['line', 'shift', 'part'])
                            ->whereHas('line', fn ($q) =>
                                $q->where('name', 'like', "%{$search}%")
                            )
                            ->orWhereHas('shift', fn ($q) =>
                                $q->where('name', 'like', "%{$search}%")
                            )
                            ->orWhereHas('part', fn ($q) =>
                                $q->where('part_name', 'like', "%{$search}%")
                            )
                            ->limit(50)
                            ->get()
                            ->mapWithKeys(fn ($record) => [
                                $record->id => $record->line?->name
                                    . ' | ' . $record->shift?->name
                                    . ' | ' . $record->part?->part_name
                            ])
                            ->toArray();
                    })
                    ->preload()
                    ->required(),
                Select::make('reject_type_id')
                    ->options(RejectType::pluck('name', 'id')->toArray())
                    ->searchable()
                    ->required(),
                TextInput::make('qty')
                    ->required()
                    ->numeric()
                    ->default(0),
                Textarea::make('notes')
                    ->default(null)
                    ->columnSpanFull(),
            ]);
    }
}
