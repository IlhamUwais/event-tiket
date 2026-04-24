<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Orders\OrderResource;
use App\Models\Orders;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

class ConfirmedPurchasesDayTable extends BaseWidget
{
    protected static ?string $heading = 'Pembelian terkonfirmasi (per tanggal)';

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Orders::query()
                    ->where('status', 'confirm')
                    ->withSum('orderDetails', 'qty')
                    ->with(['user', 'orderDetails.tiket.event'])
                    ->orderByDesc('id_order'),
            )
            ->deferFilters(false)
            ->columns([
                TextColumn::make('id_order')
                    ->label('ID order')
                    ->sortable()
                    ->url(fn (Orders $record): string => OrderResource::getUrl('edit', ['record' => $record])),

                TextColumn::make('user.name')
                    ->label('Pembeli')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('user.email')
                    ->label('Email')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('tanggal_order')
                    ->label('Tanggal order')
                    ->date('d M Y')
                    ->sortable(),

                TextColumn::make('order_details_sum_qty')
                    ->label('Jumlah tiket')
                    ->alignEnd()
                    ->numeric()
                    ->sortable(),

                TextColumn::make('final_price')
                    ->label('Total dibayar')
                    ->money('IDR', locale: 'id_ID')
                    ->sortable(),
            ])
            ->filters([
                Filter::make('tanggal_pembelian')
                    ->label('Tanggal')
                    ->schema([
                        DatePicker::make('date')
                            ->label('Tampilkan pembelian pada tanggal')
                            ->default(now())
                            ->native(false),
                    ])
                    ->default()
                    ->indicateUsing(function (array $data): ?string {
                        if (! ($data['date'] ?? null)) {
                            return null;
                        }

                        return 'Tanggal: '.Carbon::parse($data['date'])->locale(app()->getLocale())->translatedFormat('d M Y');
                    })
                    ->query(function (Builder $query, array $data): Builder {
                        $date = $data['date'] ?? now()->format('Y-m-d');

                        return $query->whereDate('tanggal_order', $date);
                    }),
            ])
            ->defaultPaginationPageOption(10);
    }
}
