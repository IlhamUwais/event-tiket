<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Event extends Model
{
    use HasFactory;

    protected $table = 'events';
    protected $primaryKey = 'id_event';

    protected $fillable = [
        'id_venue',
        'nama_event',
        'deskripsi',
        'tanggal_event',
        'jam_mulai',
        'jam_selesai',
        'gambar',
    ];

    protected $casts = [
        'tanggal_event' => 'date',
    ];

    public function venue(): BelongsTo
    {
        return $this->belongsTo(Venue::class, 'id_venue', 'id_venue');
    }

    public function tikets(): HasMany
    {
        return $this->hasMany(Tiket::class, 'id_event', 'id_event');
    }
}
?>