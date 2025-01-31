<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    public function marketing()
    {
        return $this->belongsTo(Marketing::class);
    }
}
