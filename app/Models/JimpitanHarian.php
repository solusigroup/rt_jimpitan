<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JimpitanHarian extends Model
{
    protected $table = 'jimpitan_harian';

    public $timestamps = false;

    protected $fillable = [
        'tanggal',
        'warga_id',
        'status',
        'nominal',
    ];

    public function warga(): BelongsTo
    {
        return $this->belongsTo(Warga::class, 'warga_id', 'id');
    }
}
