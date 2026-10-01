<?php

namespace App\Http\Controllers\Admin;

use App\Models\JenisPajak;
use App\Models\Kabupaten;
use App\Models\TransaksiUtang;

class AdminDashboardController
{
    public function index()
    {
        $totalUtang = (int) TransaksiUtang::sum('utang');
        $totalPembayaran = (int) TransaksiUtang::sum('pembayaran');
        $totalSisa = $totalUtang - $totalPembayaran;

        $lastRecords = TransaksiUtang::query()
            ->with(['kabupaten:id,nama', 'jenisPajak:id,nama'])
            ->orderByDesc('updated_at')
            ->take(8)
            ->get();

        $stats = [
            'totalRecord' => TransaksiUtang::count(),
            'totalKabupaten' => Kabupaten::count(),
            'totalJenisPajak' => JenisPajak::count(),
            'totalUtang' => $totalUtang,
            'totalPembayaran' => $totalPembayaran,
            'totalSisa' => $totalSisa,
        ];

        $quickLinks = [
            [
                'title' => 'Kelola Kabupaten/Kota',
                'desc' => 'Tambah, ubah, atau hapus wilayah penerima DBH.',
                'route' => 'admin.kabupaten',
                'icon' => 'map',
                'color' => 'bg-blue-50 text-blue-600',
            ],
            [
                'title' => 'Kelola Jenis Pajak',
                'desc' => 'Kelola kategori pajak: PBB KB, PAP, PKB, BBN KB, dll.',
                'route' => 'admin.jenis_pajak',
                'icon' => 'tag',
                'color' => 'bg-emerald-50 text-emerald-600',
            ],
            [
                'title' => 'Kelola Transaksi Utang',
                'desc' => 'Rekap rincian utang dan pembayaran per triwulan.',
                'route' => 'admin.transaksi',
                'icon' => 'list',
                'color' => 'bg-amber-50 text-amber-600',
            ],
        ];

        return view('admin.dashboard', compact('lastRecords', 'stats', 'quickLinks'));
    }
}
