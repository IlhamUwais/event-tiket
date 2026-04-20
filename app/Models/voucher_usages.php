<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class voucher_usages extends Model
{
    protected $table = 'voucher_usages';

    protected $fillable = [
        'id_voucher',
        'id_order',
        'id_user',
    ];

    public function voucher()
    {
        return $this->belongsTo(voucher::class, 'id_voucher', 'id_voucher');
    }

    public function order()
    {
        return $this->belongsTo(orders::class, 'id_order', 'id_order');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
}
?>