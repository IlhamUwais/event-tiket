<?php

namespace App\Filament\Resources\Tikets\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class TiketForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('id_event')
                    ->label('Event')
                    ->relationship('event', 'nama_event')
                    ->searchable()
                    ->preload()
                    ->required(),

                TextInput::make('nama_tiket')
                    ->label('Nama Tiket')
                    ->required()
                    ->maxLength(255),

                TextInput::make('harga')
                    ->label('Harga')
                    ->required()
                    ->numeric()
                    ->minValue(0)
                    ->prefix('Rp'),

                TextInput::make('kuota')
                    ->label('Kuota')
                    ->required()
                    ->numeric()
                    ->minValue(0),
            ]);
    }
}
