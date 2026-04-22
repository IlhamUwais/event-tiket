<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order {{ $order->id_order }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #111827;
            margin: 24px;
        }
        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        h1 {
            margin: 0;
            font-size: 22px;
        }
        .muted {
            color: #6b7280;
            font-size: 13px;
        }
        .card {
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 14px;
            margin-bottom: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #e5e7eb;
            padding: 8px;
            font-size: 13px;
            text-align: left;
        }
        th {
            background: #f3f4f6;
        }
        .right {
            text-align: right;
        }
        .btn-print {
            border: 1px solid #111827;
            background: #111827;
            color: #fff;
            border-radius: 6px;
            padding: 8px 12px;
            cursor: pointer;
        }
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                margin: 12px;
            }
        }
    </style>
</head>
<body>
    <div class="topbar">
        <div>
            <h1>Order #{{ $order->id_order }}</h1>
            <div class="muted">Gunakan Print lalu pilih "Save as PDF".</div>
        </div>
        <button class="btn-print no-print" onclick="window.print()">Print / Save PDF</button>
    </div>

    <div class="card">
        <strong>Pembeli:</strong> {{ $order->user?->name ?? '-' }}<br>
        <strong>Email:</strong> {{ $order->user?->email ?? '-' }}<br>
        <strong>Tanggal Order:</strong> {{ $order->tanggal_order?->format('d M Y') ?? '-' }}<br>
        <strong>Status:</strong> {{ strtoupper($order->status ?? '-') }}
    </div>

    <div class="card">
        <strong>Rincian Tiket</strong>
        <table>
            <thead>
                <tr>
                    <th>Tiket</th>
                    <th>Event</th>
                    <th class="right">Qty</th>
                    <th class="right">Harga</th>
                    <th class="right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($order->orderDetails as $detail)
                    <tr>
                        <td>{{ $detail->tiket?->nama_tiket ?? '-' }}</td>
                        <td>{{ $detail->tiket?->event?->nama_event ?? '-' }}</td>
                        <td class="right">{{ (int) $detail->qty }}</td>
                        <td class="right">Rp {{ number_format((float) $detail->price, 0, ',', '.') }}</td>
                        <td class="right">Rp {{ number_format((float) $detail->subtotal, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">Tidak ada detail order.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="card">
        <strong>Total:</strong> Rp {{ number_format((float) $order->total_price, 0, ',', '.') }}<br>
        <strong>Diskon:</strong> Rp {{ number_format((float) $order->discount, 0, ',', '.') }}<br>
        <strong>Final Price:</strong> Rp {{ number_format((float) $order->final_price, 0, ',', '.') }}
    </div>
</body>
</html>
