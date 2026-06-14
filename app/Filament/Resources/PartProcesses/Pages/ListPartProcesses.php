<?php

namespace App\Filament\Resources\PartProcesses\Pages;

use App\Filament\Resources\PartProcesses\PartProcessResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPartProcesses extends ListRecords
{
    protected static string $resource = PartProcessResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
