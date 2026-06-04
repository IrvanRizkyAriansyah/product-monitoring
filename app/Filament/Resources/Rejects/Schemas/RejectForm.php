<?php

namespace App\Filament\Resources\Rejects\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class RejectForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('report_id')
                    ->required()
                    ->numeric(),
                TextInput::make('reject_type_id')
                    ->required()
                    ->numeric(),
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
