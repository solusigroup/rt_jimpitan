<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Warga extends Model
{
    protected $table = 'warga';

    public $timestamps = false;

    protected $fillable = [
        'nama',
        'no_rumah',
        'no_wa',
        'foto',
        'status_aktif',
    ];

    protected $casts = [
        'status_aktif' => 'boolean',
    ];

    public function jadwalMasters(): HasMany
    {
        return $this->hasMany(JadwalMaster::class, 'warga_id', 'id');
    }

    public function jimpitanHarians(): HasMany
    {
        return $this->hasMany(JimpitanHarian::class, 'warga_id', 'id');
    }
}
