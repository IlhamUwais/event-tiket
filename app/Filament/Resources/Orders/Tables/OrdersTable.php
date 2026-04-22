<?php

namespace App\Filament\Resources\Orders\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\Action;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Attende;
use App\Models\OrderDetail;
use App\Models\Orders;
use App\Services\OrderCancellationService;

class OrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id_order')
                    ->label('Order ID')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('user.name')
                    ->label('User')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('final_price')
                    ->label('Final Price')
                    ->money('IDR', locale: 'id_ID')
                    ->sortable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'paid' => 'info',
                        'confirm' => 'success',
                        'cancel' => 'danger',
                        default => 'gray',
                    })
                    ->sortable(),

                TextColumn::make('tanggal_order')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'pending' => 'pending',
                        'paid' => 'paid',
                        'confirm' => 'confirm',
                        'cancel' => 'cancel',
                    ]),
            ])
            ->recordActions([
                ViewAction::make()
                    ->extraModalFooterActions(fn (Orders $record): array => [
                        Action::make('export_pdf')
                            ->label('Export PDF')
                            ->icon('heroicon-o-arrow-down-tray')
                            ->color('gray')
                            ->url(route('admin.orders.export-pdf', $record->id_order), shouldOpenInNewTab: true),
                    ]),

                Action::make('approve')
                    ->label('Approve')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn (Orders $record) => $record->status === 'paid')
                    ->action(function (Orders $record) {
                        DB::transaction(function () use ($record) {
                            $order = Orders::query()
                                ->where('id_order', $record->id_order)
                                ->lockForUpdate()
                                ->firstOrFail();

                            if ($order->status !== 'paid') {
                                return;
                            }

                            $details = OrderDetail::query()
                                ->where('id_order', $order->id_order)
                                ->get();

                            foreach ($details as $detail) {
                                $existing = Attende::query()
                                    ->where('id_detail', $detail->id_detail)
                                    ->count();

                                $need = max(0, (int) $detail->qty - (int) $existing);
                                for ($i = 0; $i < $need; $i++) {
                                    // generate unique kode_tiket
                                    do {
                                        $code = Str::upper(Str::random(10));
                                        $exists = Attende::query()->where('kode_tiket', $code)->exists();
                                    } while ($exists);

                                    Attende::create([
                                        'id_detail' => $detail->id_detail,
                                        'kode_tiket' => $code,
                                        'status' => 'belum',
                                        'waktu_checkin' => null,
                                    ]);
                                }
                            }

                            $order->update(['status' => 'confirm']);
                        });
                    }),

                Action::make('cancel')
                    ->label('Cancel')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->visible(fn (Orders $record) => $record->status !== 'cancel')
                    ->action(function (Orders $record) {
                        DB::transaction(function () use ($record) {
                            $order = Orders::query()
                                ->with('orderDetails')
                                ->where('id_order', $record->id_order)
                                ->lockForUpdate()
                                ->firstOrFail();

                            if ($order->status === 'cancel') {
                                return;
                            }

                            app(OrderCancellationService::class)->releaseReservedInventory($order);

                            $order->update([
                                'status' => 'cancel',
                                'cancel_reason' => 'admin',
                            ]);
                        });
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([]),
            ]);
    }
}
