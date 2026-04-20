<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('q', ''));

        $events = Event::query()
            ->with('venue')
            ->when($search !== '', fn ($q) => $q->where('nama_event', 'like', "%{$search}%"))
            ->orderByDesc('tanggal_event')
            ->paginate(9)
            ->withQueryString();

        return view('events.index', [
            'events' => $events,
            'search' => $search,
        ]);
    }

    public function show(int $id_event)
    {
        $event = Event::query()
            ->with(['venue', 'tikets'])
            ->where('id_event', $id_event)
            ->firstOrFail();

        return view('events.show', [
            'event' => $event,
        ]);
    }
}

