<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KasPengeluaran extends Model
{
    protected $table = 'kas_pengeluaran';

    public $timestamps = false;

    protected $fillable = [
        'tanggal',
        'keterangan',
        'nominal',
    ];
}
