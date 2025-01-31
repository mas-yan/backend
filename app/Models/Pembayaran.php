<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    protected $guarded = ['id'];

    public function penjualan()
    {
        return $this->belongsTo(Penjualan::class);
    }
}
