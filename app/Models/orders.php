<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Orders extends Model
{
    protected $table = 'orders';
    protected $primaryKey = 'id_order';

    protected $fillable = [
        'id_user',
        'id_voucher',
        'tanggal_order',
        'total_price',
        'discount',
        'final_price',
        'status',
        'expired_at',
        'cancel_reason',
    ];

    protected $casts = [
        'tanggal_order' => 'date',
        'expired_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function voucher(): BelongsTo
    {
        return $this->belongsTo(Voucher::class, 'id_voucher', 'id_voucher');
    }

    public function orderDetails(): HasMany
    {
        return $this->hasMany(OrderDetail::class, 'id_order', 'id_order');
    }
}
?>