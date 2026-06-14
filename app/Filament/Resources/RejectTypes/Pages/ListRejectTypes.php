<?php

namespace App\Filament\Resources\RejectTypes\Pages;

use App\Filament\Resources\RejectTypes\RejectTypeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListRejectTypes extends ListRecords
{
    protected static string $resource = RejectTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
