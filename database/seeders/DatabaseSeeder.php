<?php

namespace Database\Seeders;

use App\Models\JenisPajak;
use App\Models\Kabupaten;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Akun default (password: admin123 / operator123)
        \App\Models\User::firstOrCreate(
            ['email' => 'admin@utang.test'],
            ['name' => 'Administrator', 'password' => 'admin123', 'role' => \App\Models\User::ROLE_ADMIN]
        );
        \App\Models\User::firstOrCreate(
            ['email' => 'operator@utang.test'],
            ['name' => 'Operator', 'password' => 'operator123', 'role' => \App\Models\User::ROLE_OPERATOR]
        );

        $kabupatens = [
            'KOTA BENGKULU', 'REJANG LEBONG', 'BENGKULU SELATAN', 'BENGKULU UTARA',
            'LEBONG', 'KAUR', 'KEPAHIANG', 'MUKOMUKO', 'SELUMA', 'BENGKULU TENGAH',
        ];

        foreach ($kabupatens as $nama) {
            Kabupaten::firstOrCreate(['nama' => $nama]);
        }

        $pajak = [
            ['kode' => 'PBB_KB', 'nama' => 'PBB KB'],
            ['kode' => 'PAP', 'nama' => 'PAP'],
            ['kode' => 'PKB', 'nama' => 'PKB'],
            ['kode' => 'BBN_KB', 'nama' => 'BBN KB'],
        ];
        foreach ($pajak as $row) {
            JenisPajak::firstOrCreate(['kode' => $row['kode']], $row);
        }

        $rows = json_decode(
            file_get_contents(database_path('seeders/seed_data.json')),
            true
        );

        $kab = Kabupaten::pluck('id', 'nama');
        $jpk = JenisPajak::pluck('id', 'nama');

        $inserts = [];
        foreach ($rows as $r) {
            $inserts[] = [
                'tahun_anggaran' => $r['tahun'],
                'kabupaten_id' => $kab[$r['kabupaten']],
                'jenis_pajak_id' => $jpk[$r['jenis_pajak']],
                'triwulan' => $r['triwulan'],
                'utang' => $r['utang'],
                'pembayaran' => $r['pembayaran'],
                'sisa' => $r['sisa'],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        if (!empty($inserts)) {
            DB::table('transaksi_utang')->insert($inserts);
        }
    }
}
