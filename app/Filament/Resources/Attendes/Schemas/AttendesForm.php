<?php

namespace App\Filament\Resources\Attendes\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class AttendesForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('id_detail')
                    ->label('Order Detail')
                    ->relationship('orderDetail', 'id_detail')
                    ->searchable()
                    ->preload()
                    ->required(),

                TextInput::make('kode_tiket')
                    ->label('Kode Tiket')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),

                Select::make('status')
                    ->label('Status')
                    ->options([
                        'belum' => 'belum',
                        'sudah' => 'sudah',
                    ])
                    ->required()
                    ->default('belum'),

                DateTimePicker::make('waktu_checkin')
                    ->label('Waktu Checkin')
                    ->seconds(false),
            ]);
    }
}
