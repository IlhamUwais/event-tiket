<?php

namespace App\Filament\Resources\Orders\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('id_user')
                    ->label('User')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->disabled()
                    ->dehydrated(false),

                Select::make('id_voucher')
                    ->label('Voucher')
                    ->relationship('voucher', 'code')
                    ->searchable()
                    ->preload()
                    ->disabled()
                    ->dehydrated(false),

                DatePicker::make('tanggal_order')
                    ->label('Tanggal Order')
                    ->disabled()
                    ->dehydrated(false),

                TextInput::make('total_price')
                    ->label('Total Price')
                    ->numeric()
                    ->disabled()
                    ->dehydrated(false),

                TextInput::make('discount')
                    ->label('Discount')
                    ->numeric()
                    ->disabled()
                    ->dehydrated(false),

                TextInput::make('final_price')
                    ->label('Final Price')
                    ->numeric()
                    ->disabled()
                    ->dehydrated(false),

                TextInput::make('status')
                    ->label('Status')
                    ->disabled()
                    ->dehydrated(false),

                TextInput::make('expired_at')
                    ->label('Expired At')
                    ->disabled()
                    ->dehydrated(false),

                TextInput::make('cancel_reason')
                    ->label('Cancel Reason')
                    ->disabled()
                    ->dehydrated(false),
            ]);
    }
}
