<?php

namespace App\Filament\Resources\PartProcesses\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PartProcessForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('part_id')
                    ->relationship('part', 'part_name')
                    ->preload()
                    ->searchable()
                    ->required(),

                Select::make('process_id')
                    ->relationship('process', 'process_name')
                    ->searchable()
                    ->preload()
                    ->required(),

                Select::make('machine_id')
                    ->relationship('machine', 'machine_name')
                    ->searchable()
                    ->preload()
                    ->required(),

                TextInput::make('sequence')
                    ->numeric()
                    ->required(),

                TextInput::make('std_run')
                    ->numeric()
                    ->required(),
            ]);
    }
}
