<?php

namespace App\Filament\Resources\Events\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class EventsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('gambar')
                    ->label('Gambar')
                    ->square()
                    ->size(48),

                TextColumn::make('nama_event')
                    ->label('Nama Event')
                    ->searchable()
                    ->sortable()
                    ->weight('medium'),

                TextColumn::make('venue.nama_venue')
                    ->label('Venue')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('tanggal_event')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),

                TextColumn::make('jam_mulai')
                    ->label('Jam Mulai')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('id_venue')
                    ->label('Venue')
                    ->relationship('venue', 'nama_venue')
                    ->searchable()
                    ->preload(),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
