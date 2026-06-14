<?php

namespace App\Filament\Pages;

use App\Models\Line;
use App\Models\Shift;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;

class Dashboard extends BaseDashboard
{
    use HasFiltersForm;

    public function mount(): void
    {
        $this->filters = [
            'start_date' => today()->toDateString(),
            'end_date'   => today()->toDateString(),
        ];
    }

    public function filtersForm(Schema $schema): Schema
    {
        return $schema->components([
            Section::make()
                ->columnSpanFull()
                ->columns(2)
                ->schema([
                    DatePicker::make('start_date')
                        ->live()
                        ->default(now()->startOfMonth()),
                        

                    DatePicker::make('end_date')
                        ->minDate(fn ($get) => $get('start_date'))
                        ->live()
                        ->default(now()),

                    Select::make('line_id')
                        ->label('Line')
                        ->options(Line::pluck('name', 'id')->toArray())
                        ->searchable(),

                    Select::make('shift_id')
                        ->label('Shift')
                        ->options(Shift::pluck('name', 'id')->toArray())
                        ->searchable(),
                ]),
        ]);
    }

    public function getMaxContentWidth(): ?string
    {
        return 'full';
    }
}