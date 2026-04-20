<?php

namespace App\Filament\Resources\Attendes\Pages;

use App\Filament\Resources\Attendes\AttendesResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditAttendes extends EditRecord
{
    protected static string $resource = AttendesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
