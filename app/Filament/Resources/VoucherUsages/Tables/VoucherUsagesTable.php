<?php

namespace App\Filament\Resources\VoucherUsages\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class VoucherUsagesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('voucher.code')
                    ->label('Voucher')
                    ->searchable()
                    ->sortable()
                    ->weight('medium'),

                TextColumn::make('user.name')
                    ->label('User')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('order.id_order')
                    ->label('Order ID')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('id_voucher')
                    ->label('Voucher')
                    ->relationship('voucher', 'code')
                    ->searchable()
                    ->preload(),
            ])
            ->recordActions([
                //
            ])
            ->toolbarActions([
                BulkActionGroup::make([]),
            ]);
    }
}
