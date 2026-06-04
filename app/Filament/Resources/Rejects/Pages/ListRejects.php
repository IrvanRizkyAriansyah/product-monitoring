<?php

namespace App\Filament\Resources\Rejects\Pages;

use App\Filament\Resources\Rejects\RejectResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListRejects extends ListRecords
{
    protected static string $resource = RejectResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
