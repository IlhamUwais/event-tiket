<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Voucher extends Model
{
    protected $table = 'vouchers';
    protected $primaryKey = 'id_voucher';

    protected $fillable = [
        'code',
        'discount_percent',
        'usage_limit',
        'used_count',
        'status',
    ];

    public function orders(): HasMany
    {
        return $this->hasMany(Orders::class, 'id_voucher', 'id_voucher');
    }
}

?>
