<?php

namespace App\Filament\Resources\Machines\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class MachineForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('line_id')
                    ->label('Line')
                    ->relationship('line', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                TextInput::make('machine_code')
                    ->required(),
                TextInput::make('machine_name')
                    ->required(),
                Select::make('status')
                    ->options(['active' => 'Active', 'maintenance' => 'Maintenance', 'inactive' => 'Inactive'])
                    ->default('active')
                    ->required(),
            ]);
    }
}
