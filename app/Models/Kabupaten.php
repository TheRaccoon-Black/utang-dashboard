<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kabupaten extends Model
{
    protected $fillable = ['nama'];

    public function transaksi()
    {
        return $this->hasMany(TransaksiUtang::class, 'kabupaten_id');
    }
}
