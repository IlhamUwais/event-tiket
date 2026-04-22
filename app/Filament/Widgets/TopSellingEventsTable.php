<?php

namespace App\Filament\Widgets;

use App\Models\Event;
use App\Models\OrderDetail;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class TopSellingEventsTable extends BaseWidget
{
    protected static ?string $heading = 'Event Paling Laris';

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        $paidStatuses = ['paid', 'confirm'];

        return $table
            ->query(
                Event::query()
                    ->select('events.*')
                    ->selectSub(
                        OrderDetail::query()
                            ->selectRaw('COALESCE(SUM(order_details.qty), 0)')
                            ->join('orders', 'orders.id_order', '=', 'order_details.id_order')
                            ->join('tikets', 'tikets.id_tiket', '=', 'order_details.id_tiket')
                            ->whereColumn('tikets.id_event', 'events.id_event')
                            ->whereIn('orders.status', $paidStatuses),
                        'tickets_sold'
                    )
                    ->selectSub(
                        OrderDetail::query()
                            ->selectRaw('COALESCE(SUM(order_details.subtotal), 0)')
                            ->join('orders', 'orders.id_order', '=', 'order_details.id_order')
                            ->join('tikets', 'tikets.id_tiket', '=', 'order_details.id_tiket')
                            ->whereColumn('tikets.id_event', 'events.id_event')
                            ->whereIn('orders.status', $paidStatuses),
                        'revenue'
                    )
                    ->with('venue')
                    ->orderByDesc('tickets_sold')
            )
            ->defaultPaginationPageOption(5)
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

                TextColumn::make('tickets_sold')
                    ->label('Tiket Terjual')
                    ->numeric()
                    ->sortable(query: fn (Builder $query, string $direction): Builder => $query->orderBy('tickets_sold', $direction)),

                TextColumn::make('revenue')
                    ->label('Pendapatan')
                    ->money('IDR', locale: 'id_ID')
                    ->sortable(query: fn (Builder $query, string $direction): Builder => $query->orderBy('revenue', $direction)),

                TextColumn::make('id_event')
                    ->label('Detail')
                    ->formatStateUsing(fn () => 'Lihat')
                    ->url(fn (Event $record): string => route('filament.admin.resources.events.edit', ['record' => $record->id_event]))
                    ->openUrlInNewTab(),
            ]);
    }
}
