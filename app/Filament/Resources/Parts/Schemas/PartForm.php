<?php

namespace App\Filament\Resources\Parts\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PartForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('part_no')
                    ->required(),
                TextInput::make('part_name')
                    ->required(),
                TextInput::make('std_run')
                    ->required()
                    ->numeric()
                    ->default(0.0),
            ]);
    }
}
