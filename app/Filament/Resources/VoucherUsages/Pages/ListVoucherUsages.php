<?php

namespace App\Filament\Resources\VoucherUsages\Pages;

use App\Filament\Resources\VoucherUsages\VoucherUsageResource;
use Filament\Resources\Pages\ListRecords;

class ListVoucherUsages extends ListRecords
{
    protected static string $resource = VoucherUsageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //
        ];
    }
}
