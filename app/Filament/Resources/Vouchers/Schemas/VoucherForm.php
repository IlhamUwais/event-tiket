<?php

namespace App\Filament\Resources\Vouchers\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class VoucherForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('code')
                    ->label('Code')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),

                TextInput::make('discount_percent')
                    ->label('Discount Percent')
                    ->required()
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(100)
                    ->suffix('%'),

                TextInput::make('usage_limit')
                    ->label('Usage Limit')
                    ->required()
                    ->numeric()
                    ->minValue(0),

                TextInput::make('used_count')
                    ->label('Used Count')
                    ->numeric()
                    ->disabled()
                    ->dehydrated(false)
                    ->default(0),

                Select::make('status')
                    ->label('Status')
                    ->options([
                        'aktif' => 'aktif',
                        'nonaktif' => 'nonaktif',
                    ])
                    ->required()
                    ->default('aktif'),
            ]);
    }
}
