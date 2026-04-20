<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class order_detail extends Model
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

    public function order()
    {
        return $this->belongsTo(orders::class, 'id_order', 'id_order');
    }

    public function tiket()
    {
        return $this->belongsTo(tiket::class, 'id_tiket', 'id_tiket');
    }

    public function attendes()
    {
        return $this->hasMany(attende::class, 'id_detail', 'id_detail');
    }
}
?>