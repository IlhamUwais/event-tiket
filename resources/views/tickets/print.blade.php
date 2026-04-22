@php
    $event = $attende->orderDetail?->tiket?->event;
    $tiket = $attende->orderDetail?->tiket;
    $venue = $event?->venue;
    $user = $attende->orderDetail?->order?->user;
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tiket — {{ $event?->nama_event ?? 'Event' }}</title>
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            padding: 24px;
            font-family: ui-sans-serif, system-ui, sans-serif;
            color: #0f172a;
            background: #fff;
        }
        .wrap { max-width: 480px; margin: 0 auto; }
        h1 { font-size: 1.25rem; margin: 0 0 8px; }
        .muted { color: #64748b; font-size: 0.875rem; }
        dl { margin: 24px 0; font-size: 0.9rem; }
        dt { font-weight: 600; margin-top: 12px; color: #334155; }
        dd { margin: 4px 0 0; }
        .qr { text-align: center; margin: 24px 0; padding: 16px; border: 1px solid #e2e8f0; border-radius: 12px; }
        .code { font-size: 1.125rem; font-weight: 700; letter-spacing: 0.05em; }
        @media print {
            body { padding: 12px; }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body>
    <div class="wrap">
        <p class="no-print muted" style="margin-bottom: 16px;">
            Gunakan Ctrl+P atau menu cetak browser untuk mencetak tiket.
        </p>

        <h1>{{ $event?->nama_event ?? '-' }}</h1>
        <p class="muted">
            {{ $event?->tanggal_event?->format('d M Y') ?? '-' }}
            @if($event?->jam_mulai)
                · {{ $event->jam_mulai }}
                @if($event->jam_selesai)
                    – {{ $event->jam_selesai }}
                @endif
            @endif
        </p>

        <dl>
            <dt>Venue</dt>
            <dd>{{ $venue?->nama_venue ?? '-' }}</dd>
            @if($venue?->alamat)
                <dd class="muted" style="margin-top: 4px;">{{ $venue->alamat }}</dd>
            @endif

            <dt>Jenis tiket</dt>
            <dd>{{ $tiket?->nama_tiket ?? '-' }}</dd>

            <dt>Nama pemilik</dt>
            <dd>{{ $user?->name ?? '-' }}</dd>

            <dt>Kode tiket</dt>
            <dd class="code">{{ $attende->kode_tiket }}</dd>
        </dl>

        <div class="qr">
            @php
                echo \SimpleSoftwareIO\QrCode\Facades\QrCode::size(200)->margin(1)->generate($attende->kode_tiket);
            @endphp
        </div>
    </div>
</body>
</html>
