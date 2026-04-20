<?php

namespace App\Filament\Resources\VoucherUsages\Schemas;

use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class VoucherUsageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('id_voucher')
                    ->label('Voucher')
                    ->relationship('voucher', 'code')
                    ->disabled()
                    ->dehydrated(false),

                Select::make('id_user')
                    ->label('User')
                    ->relationship('user', 'name')
                    ->disabled()
                    ->dehydrated(false),

                Select::make('id_order')
                    ->label('Order')
                    ->relationship('order', 'id_order')
                    ->disabled()
                    ->dehydrated(false),
            ]);
    }
}
