<?php

namespace App\Filament\Resources\VoucherUsages\Pages;

use App\Filament\Resources\VoucherUsages\VoucherUsageResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditVoucherUsage extends EditRecord
{
    protected static string $resource = VoucherUsageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
