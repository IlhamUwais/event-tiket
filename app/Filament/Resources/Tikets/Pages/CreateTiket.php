<?php

namespace App\Filament\Resources\Tikets\Pages;

use App\Filament\Resources\Tikets\TiketResource;
use App\Models\Tiket;
use Filament\Resources\Pages\CreateRecord;

class CreateTiket extends CreateRecord
{
    protected static string $resource = TiketResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        Tiket::assertTotalKuotaWithinVenueCapacity((int) $data['id_event'], (int) $data['kuota'], null);

        return $data;
    }
}
