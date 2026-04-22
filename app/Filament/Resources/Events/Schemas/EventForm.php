<?php

namespace App\Filament\Resources\Events\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Schema;

class EventForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('id_venue')
                    ->label('Venue')
                    ->relationship('venue', 'nama_venue')
                    ->searchable()
                    ->preload()
                    ->required(),

                TextInput::make('nama_event')
                    ->label('Nama Event')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),

                Textarea::make('deskripsi')
                    ->label('Deskripsi')
                    ->required()
                    ->rows(5)
                    ->columnSpanFull(),

                DatePicker::make('tanggal_event')
                    ->label('Tanggal Event')
                    ->required(),

                TimePicker::make('jam_mulai')
                    ->label('Jam Mulai')
                    ->required()
                    ->seconds(false),

                TimePicker::make('jam_selesai')
                    ->label('Jam Selesai')
                    ->required()
                    ->seconds(false),

                FileUpload::make('gambar')
                    ->label('Gambar')
                    ->image()
                    ->imageEditor()
                    ->disk('public')
                    ->directory('events')
                    ->required(),
            ]);
    }
}
