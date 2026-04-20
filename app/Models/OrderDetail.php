<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OrderDetail extends Model
{
    protected $table = 'order_details';
    protected $primaryKey = 'id_detail';

    protected $fillable = [
        'id_order',
        'id_tiket',
        'qty',
        'price',
        'subtotal',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Orders::class, 'id_order', 'id_order');
    }

    public function tiket(): BelongsTo
    {
        return $this->belongsTo(Tiket::class, 'id_tiket', 'id_tiket');
    }

    public function attendes(): HasMany
    {
        return $this->hasMany(Attende::class, 'id_detail', 'id_detail');
    }
}

?>