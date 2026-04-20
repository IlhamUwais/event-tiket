<?php

namespace App\Filament\Resources\Attendes\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use App\Models\Attende;

class AttendesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kode_tiket')
                    ->label('Kode Tiket')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->weight('medium'),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'belum' => 'warning',
                        'sudah' => 'success',
                        default => 'gray',
                    })
                    ->sortable(),

                TextColumn::make('waktu_checkin')
                    ->label('Waktu Checkin')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->placeholder('-'),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'belum' => 'belum',
                        'sudah' => 'sudah',
                    ]),
            ])
            ->recordActions([
                EditAction::make(),

                Action::make('checkin')
                    ->label('Check In')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn (Attende $record) => $record->status === 'belum')
                    ->action(function (Attende $record) {
                        $record->update([
                            'status' => 'sudah',
                            'waktu_checkin' => now(),
                        ]);
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
