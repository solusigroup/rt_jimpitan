<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KasSetting extends Model
{
    protected $table = 'kas_setting';

    public $timestamps = false;

    // The primary key is static ID 1, not auto-incrementing
    public $incrementing = false;

    protected $fillable = [
        'id',
        'saldo_awal',
    ];
}
