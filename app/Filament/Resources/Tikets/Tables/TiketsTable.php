<?php

namespace App\Filament\Resources\Tikets\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class TiketsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('event.nama_event')
                    ->label('Event')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('nama_tiket')
                    ->label('Nama Tiket')
                    ->searchable()
                    ->sortable()
                    ->weight('medium'),

                TextColumn::make('harga')
                    ->label('Harga')
                    ->money('IDR', locale: 'id_ID')
                    ->sortable(),

                TextColumn::make('kuota')
                    ->label('Kuota')
                    ->numeric()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('id_event')
                    ->label('Event')
                    ->relationship('event', 'nama_event')
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
