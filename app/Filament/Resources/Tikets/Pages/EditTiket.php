<?php

namespace App\Filament\Resources\Tikets\Pages;

use App\Filament\Resources\Tikets\TiketResource;
use App\Models\Tiket;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTiket extends EditRecord
{
    protected static string $resource = TiketResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        Tiket::assertTotalKuotaWithinVenueCapacity(
            (int) $data['id_event'],
            (int) $data['kuota'],
            (int) $this->record->id_tiket,
        );

        return $data;
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
