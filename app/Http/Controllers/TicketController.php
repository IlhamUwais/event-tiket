<?php

namespace App\Http\Controllers;

use App\Models\Attende;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function show(Request $request, string $kode_tiket)
    {
        $attende = Attende::query()
            ->with(['orderDetail.order'])
            ->where('kode_tiket', $kode_tiket)
            ->firstOrFail();

        $order = $attende->orderDetail?->order;
        abort_unless($order && (int) $order->id_user === (int) $request->user()->id_user, 404);

        return view('tickets.show', [
            'attende' => $attende,
        ]);
    }

    public function print(Request $request, string $kode_tiket)
    {
        $attende = Attende::query()
            ->with(['orderDetail.tiket.event.venue', 'orderDetail.order.user'])
            ->where('kode_tiket', $kode_tiket)
            ->firstOrFail();

        $order = $attende->orderDetail?->order;
        abort_unless($order && (int) $order->id_user === (int) $request->user()->id_user, 404);

        return view('tickets.print', [
            'attende' => $attende,
        ]);
    }
}

