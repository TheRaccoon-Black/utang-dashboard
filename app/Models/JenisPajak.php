<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JenisPajak extends Model
{
    protected $table = 'jenis_pajak';

    protected $fillable = ['kode', 'nama'];

    public function transaksi()
    {
        return $this->hasMany(TransaksiUtang::class, 'jenis_pajak_id');
    }
}
