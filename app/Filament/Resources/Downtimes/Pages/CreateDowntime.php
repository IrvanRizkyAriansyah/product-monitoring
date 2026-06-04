<?php

namespace App\Filament\Resources\Downtimes\Pages;

use App\Filament\Resources\Downtimes\DowntimeResource;
use Filament\Resources\Pages\CreateRecord;

class CreateDowntime extends CreateRecord
{
    protected static string $resource = DowntimeResource::class;
}
