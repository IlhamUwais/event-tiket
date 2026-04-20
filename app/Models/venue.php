<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Venue extends Model
{
    protected $table = 'venues';
    protected $primaryKey = 'id_venue';

    protected $fillable = [
        'nama_venue',
        'alamat',
        'kapasitas',
    ];

    public function events(): HasMany
    {
        return $this->hasMany(Event::class, 'id_venue', 'id_venue');
    }
}

?>
