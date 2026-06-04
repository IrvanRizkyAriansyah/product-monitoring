<?php

namespace App\Filament\Resources\Downtimes\Pages;

use App\Filament\Resources\Downtimes\DowntimeResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditDowntime extends EditRecord
{
    protected static string $resource = DowntimeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
