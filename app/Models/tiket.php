<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Validation\ValidationException;

class Tiket extends Model
{
    protected $table = 'tikets';
    protected $primaryKey = 'id_tiket';

    protected $fillable = [
        'id_event',
        'nama_tiket',
        'harga',
        'kuota',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class, 'id_event', 'id_event');
    }

    public function orderDetails(): HasMany
    {
        return $this->hasMany(OrderDetail::class, 'id_tiket', 'id_tiket');
    }

    /**
     * Pastikan jumlah kuota (semua tiket event + kuota ini) tidak melebihi kapasitas venue.
     */
    public static function assertTotalKuotaWithinVenueCapacity(int $id_event, int $kuota, ?int $excludeTiketId = null): void
    {
        $event = Event::query()->with('venue')->where('id_event', $id_event)->firstOrFail();
        $capacity = (int) ($event->venue?->kapasitas ?? 0);

        $sumOthers = (int) static::query()
            ->where('id_event', $id_event)
            ->when($excludeTiketId !== null, fn ($q) => $q->where('id_tiket', '!=', $excludeTiketId))
            ->sum('kuota');

        if ($sumOthers + $kuota > $capacity) {
            throw ValidationException::withMessages([
                'kuota' => sprintf(
                    'Total kuota semua tiket untuk event ini (%d) melebihi kapasitas venue (%d).',
                    $sumOthers + $kuota,
                    $capacity
                ),
            ]);
        }
    }
}
