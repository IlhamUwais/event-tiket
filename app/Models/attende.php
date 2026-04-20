<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attende extends Model
{
    protected $table = 'attendes';
    protected $primaryKey = 'id_attendes';

    protected $fillable = [
        'id_detail',
        'kode_tiket',
        'status',
        'waktu_checkin',
    ];

    protected $casts = [
        'waktu_checkin' => 'datetime',
    ];

    public function orderDetail(): BelongsTo
    {
        return $this->belongsTo(OrderDetail::class, 'id_detail', 'id_detail');
    }
}
?>