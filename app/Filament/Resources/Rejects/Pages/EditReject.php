<?php

namespace App\Filament\Resources\Rejects\Pages;

use App\Filament\Resources\Rejects\RejectResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditReject extends EditRecord
{
    protected static string $resource = RejectResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
