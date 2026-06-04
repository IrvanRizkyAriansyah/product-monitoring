<?php

namespace App\Filament\Resources\ProductionReports\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ProductionReportForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('line_id')
                    ->required()
                    ->numeric(),
                TextInput::make('part_id')
                    ->required()
                    ->numeric(),
                TextInput::make('shift_id')
                    ->required()
                    ->numeric(),
                TextInput::make('leader_id')
                    ->required()
                    ->numeric(),
                DatePicker::make('report_date')
                    ->required(),
                TextInput::make('total_target')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('total_actual')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('achievement')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                Select::make('status')
                    ->options([
            'draft' => 'Draft',
            'submitted' => 'Submitted',
            'approved' => 'Approved',
            'rejected' => 'Rejected',
        ])
                    ->default('draft')
                    ->required(),
                Textarea::make('notes')
                    ->default(null)
                    ->columnSpanFull(),
                TextInput::make('approved_by')
                    ->numeric()
                    ->default(null),
                DateTimePicker::make('approved_at'),
            ]);
    }
}
