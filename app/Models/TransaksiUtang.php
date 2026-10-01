<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransaksiUtang extends Model
{
    protected $table = 'transaksi_utang';

    protected $fillable = [
        'tahun_anggaran',
        'kabupaten_id',
        'jenis_pajak_id',
        'triwulan',
        'utang',
        'pembayaran',
        'sisa',
    ];

    protected $casts = [
        'utang' => 'integer',
        'pembayaran' => 'integer',
        'sisa' => 'integer',
    ];

    public function kabupaten()
    {
        return $this->belongsTo(Kabupaten::class, 'kabupaten_id');
    }

    public function jenisPajak()
    {
        return $this->belongsTo(JenisPajak::class, 'jenis_pajak_id');
    }

    // Enforce invariant: sisa = utang - pembayaran
    public static function booted(): void
    {
    }

    public function updateSisa(): void
    {
        $this->sisa = max(0, $this->utang - $this->pembayaran);
        $this->save();
    }
}
