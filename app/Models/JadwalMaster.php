<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JadwalMaster extends Model
{
    protected $table = 'jadwal_master';

    public $timestamps = false;

    protected $fillable = [
        'warga_id',
        'hari',
        'pasaran',
    ];

    public function warga(): BelongsTo
    {
        return $this->belongsTo(Warga::class, 'warga_id', 'id');
    }
}
