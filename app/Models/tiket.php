<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
}

?>
