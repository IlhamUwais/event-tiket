<?php

namespace App\Filament\Resources\Venues\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class VenueForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nama_venue')
                    ->label('Nama Venue')
                    ->required()
                    ->maxLength(255),

                Textarea::make('alamat')
                    ->label('Alamat')
                    ->required()
                    ->rows(3)
                    ->columnSpanFull(),

                TextInput::make('kapasitas')
                    ->label('Kapasitas')
                    ->required()
                    ->numeric()
                    ->minValue(0),
            ]);
    }
}
