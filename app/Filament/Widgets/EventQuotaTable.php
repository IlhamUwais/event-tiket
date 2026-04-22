<?php

namespace App\Filament\Widgets;

use App\Models\Event;
use App\Models\OrderDetail;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class EventQuotaTable extends BaseWidget
{
    protected static ?string $heading = 'Monitoring Kuota Event';

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        $paidStatuses = ['paid', 'confirm'];

        return $table
            ->query(
                Event::query()
                    ->select('events.*')
                    ->with('venue')
                    ->withSum('tikets as total_kuota', 'kuota')
                    ->selectSub(
                        OrderDetail::query()
                            ->selectRaw('COALESCE(SUM(order_details.qty), 0)')
                            ->join('orders', 'orders.id_order', '=', 'order_details.id_order')
                            ->join('tikets', 'tikets.id_tiket', '=', 'order_details.id_tiket')
                            ->whereColumn('tikets.id_event', 'events.id_event')
                            ->whereIn('orders.status', $paidStatuses),
                        'tickets_sold'
                    )
            )
            ->defaultSort('tanggal_event')
            ->defaultPaginationPageOption(10)
            ->columns([
                TextColumn::make('nama_event')
                    ->label('Event')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('venue.nama_venue')
                    ->label('Venue')
                    ->toggleable(),

                TextColumn::make('tanggal_event')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),

                TextColumn::make('total_kuota')
                    ->label('Total Kuota')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('tickets_sold')
                    ->label('Terjual')
                    ->numeric()
                    ->sortable(query: fn (Builder $query, string $direction): Builder => $query->orderBy('tickets_sold', $direction)),

                TextColumn::make('sisa_kuota')
                    ->label('Sisa Kuota')
                    ->state(fn (Event $record): int => max(0, (int) $record->total_kuota - (int) $record->tickets_sold))
                    ->badge()
                    ->color(fn (int $state): string => match (true) {
                        $state <= 0 => 'danger',
                        $state <= 20 => 'warning',
                        default => 'success',
                    }),

                TextColumn::make('id_event')
                    ->label('Detail')
                    ->formatStateUsing(fn () => 'Lihat')
                    ->url(fn (Event $record): string => route('filament.admin.resources.events.edit', ['record' => $record->id_event]))
                    ->openUrlInNewTab(),
            ]);
    }
}
