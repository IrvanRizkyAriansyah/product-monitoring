<?php

namespace App\Filament\Resources\RejectTypes\Pages;

use App\Filament\Resources\RejectTypes\RejectTypeResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditRejectType extends EditRecord
{
    protected static string $resource = RejectTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
