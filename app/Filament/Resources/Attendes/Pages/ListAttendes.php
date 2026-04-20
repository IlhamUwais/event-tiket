<?php

namespace App\Filament\Resources\Attendes\Pages;

use App\Filament\Resources\Attendes\AttendesResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAttendes extends ListRecords
{
    protected static string $resource = AttendesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
