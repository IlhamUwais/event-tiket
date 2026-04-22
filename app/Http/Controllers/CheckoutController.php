<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\OrderDetail;
use App\Models\Orders;
use App\Models\Tiket;
use App\Models\Voucher;
use App\Models\voucher_usages;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CheckoutController extends Controller
{
    public function create(int $id_event)
    {
        $event = Event::query()
            ->with(['venue', 'tikets'])
            ->where('id_event', $id_event)
            ->firstOrFail();

        return view('checkout.create', [
            'event' => $event,
        ]);
    }

    public function voucherPreview(Request $request, int $id_event)
    {
        $data = $request->validate([
            'voucher' => ['required', 'string', 'max:255'],
        ]);

        $code = strtoupper(trim($data['voucher']));

        return DB::transaction(function () use ($request, $code) {
            $voucher = Voucher::query()
                ->where('code', $code)
                ->where('status', 'aktif')
                ->lockForUpdate()
                ->first();

            if (! $voucher) {
                return response()->json(['message' => 'Voucher tidak ditemukan atau tidak aktif.'], 422);
            }

            if ($voucher->used_count >= $voucher->usage_limit) {
                return response()->json(['message' => 'Voucher sudah mencapai limit penggunaan.'], 422);
            }

            $alreadyUsed = voucher_usages::query()
                ->where('id_voucher', $voucher->id_voucher)
                ->where('id_user', $request->user()->id_user)
                ->exists();

            if ($alreadyUsed) {
                return response()->json(['message' => 'Kamu sudah pernah memakai voucher ini.'], 422);
            }

            return response()->json([
                'discount_percent' => (int) $voucher->discount_percent,
            ]);
        });
    }

    public function store(Request $request, int $id_event)
    {
        $validated = $request->validate([
            'items' => ['required', 'array'],
            'items.*' => ['nullable', 'integer', 'min:0', 'max:100'],
            'voucher' => ['nullable', 'string', 'max:255'],
        ]);

        $items = collect($validated['items'] ?? [])
            ->map(fn ($qty) => (int) $qty)
            ->filter(fn ($qty) => $qty > 0);

        if ($items->isEmpty()) {
            throw ValidationException::withMessages([
                'items' => 'Pilih minimal 1 tiket.',
            ]);
        }

        $voucherCode = strtoupper(trim((string) ($validated['voucher'] ?? '')));
        $now = CarbonImmutable::now();

        $order = DB::transaction(function () use ($id_event, $items, $voucherCode, $now, $request) {
            $event = Event::query()
                ->where('id_event', $id_event)
                ->firstOrFail();

            $ticketIds = $items->keys()->map(fn ($v) => (int) $v)->values();

            /** @var \Illuminate\Database\Eloquent\Collection<int, Tiket> $tikets */
            $tikets = Tiket::query()
                ->where('id_event', $event->id_event)
                ->whereIn('id_tiket', $ticketIds)
                ->lockForUpdate()
                ->get()
                ->keyBy('id_tiket');

            if ($tikets->count() !== $ticketIds->count()) {
                throw ValidationException::withMessages([
                    'items' => 'Ada tiket yang tidak valid untuk event ini.',
                ]);
            }

            $total = 0;
            foreach ($items as $id_tiket => $qty) {
                $tiket = $tikets->get((int) $id_tiket);
                if (! $tiket) {
                    continue;
                }

                if ($tiket->kuota < $qty) {
                    throw ValidationException::withMessages([
                        "items.{$id_tiket}" => "Kuota tiket {$tiket->nama_tiket} tidak cukup.",
                    ]);
                }

                $total += ((int) $tiket->harga) * $qty;
            }

            $voucher = null;
            $discount = 0;

            if ($voucherCode !== '') {
                $voucher = Voucher::query()
                    ->where('code', $voucherCode)
                    ->where('status', 'aktif')
                    ->lockForUpdate()
                    ->first();

                if (! $voucher) {
                    throw ValidationException::withMessages([
                        'voucher' => 'Voucher tidak ditemukan atau tidak aktif.',
                    ]);
                }

                if ($voucher->used_count >= $voucher->usage_limit) {
                    throw ValidationException::withMessages([
                        'voucher' => 'Voucher sudah mencapai limit penggunaan.',
                    ]);
                }

                $alreadyUsed = voucher_usages::query()
                    ->where('id_voucher', $voucher->id_voucher)
                    ->where('id_user', $request->user()->id_user)
                    ->exists();

                if ($alreadyUsed) {
                    throw ValidationException::withMessages([
                        'voucher' => 'Kamu sudah pernah memakai voucher ini.',
                    ]);
                }

                $discount = (int) round($total * ((int) $voucher->discount_percent) / 100);
            }

            $final = max(0, $total - $discount);

            $order = Orders::create([
                'id_user' => $request->user()->id_user,
                'id_voucher' => $voucher !== null ? $voucher->id_voucher : null,
                'tanggal_order' => $now->toDateString(),
                'total_price' => $total,
                'discount' => (int) $discount,
                'final_price' => $final,
                'status' => 'pending',
                'expired_at' => $now->addDay(),
                'cancel_reason' => null,
            ]);

            foreach ($items as $id_tiket => $qty) {
                $tiket = $tikets->get((int) $id_tiket);

                $price = (int) $tiket->harga;
                $subtotal = $price * $qty;

                OrderDetail::create([
                    'id_order' => $order->id_order,
                    'id_tiket' => (int) $id_tiket,
                    'qty' => $qty,
                    'price' => $price,
                    'subtotal' => $subtotal,
                ]);

                $tiket->decrement('kuota', $qty);
            }

            if ($voucher) {
                voucher_usages::create([
                    'id_voucher' => $voucher->id_voucher,
                    'id_order' => $order->id_order,
                    'id_user' => $request->user()->id_user,
                ]);

                $voucher->increment('used_count');
            }

            return $order;
        });

        return redirect()->route('orders.pay', $order->id_order)
            ->with('status', 'Checkout berhasil. Silakan lakukan pembayaran sebelum pesanan expired.');
    }
}

