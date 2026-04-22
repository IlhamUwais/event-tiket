<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Orders;
use Illuminate\Http\Request;

class OrderPdfController extends Controller
{
    public function __invoke(Request $request, string $id_order)
    {
        abort_unless($request->user()?->role === 'admin', 403);

        $order = Orders::query()
            ->with([
                'user',
                'voucher',
                'orderDetails.tiket.event',
            ])
            ->where('id_order', $id_order)
            ->firstOrFail();

        return response()
            ->view('admin.orders.pdf', [
                'order' => $order,
            ])
            ->header('Content-Type', 'text/html; charset=UTF-8');
    }
}
