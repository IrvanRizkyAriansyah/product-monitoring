<?php

namespace App\Filament\Resources\PartProcesses\Pages;

use App\Filament\Resources\PartProcesses\PartProcessResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPartProcess extends EditRecord
{
    protected static string $resource = PartProcessResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
