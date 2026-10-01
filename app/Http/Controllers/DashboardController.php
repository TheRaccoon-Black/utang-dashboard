<?php

namespace App\Http\Controllers;

use App\Models\Kabupaten;
use App\Models\JenisPajak;
use App\Models\TransaksiUtang;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Multi-select filter values (array, empty = Semua)
        $tahunAnggaran   = array_filter(array_map('intval', (array) $request->input('tahun', [])));
        $kabupatenIds    = array_filter(array_map('intval', (array) $request->input('kabupaten', [])));
        $jenisPajakIds   = array_filter(array_map('intval', (array) $request->input('jenis_pajak', [])));
        $triwulanIds     = array_filter(array_map('intval', (array) $request->input('triwulan', [])));

        $query = TransaksiUtang::query()
            ->with(['kabupaten:id,nama', 'jenisPajak:id,nama,kode']);

        if (!empty($tahunAnggaran))  $query->whereIn('tahun_anggaran', $tahunAnggaran);
        if (!empty($kabupatenIds))   $query->whereIn('kabupaten_id', $kabupatenIds);
        if (!empty($jenisPajakIds))  $query->whereIn('jenis_pajak_id', $jenisPajakIds);
        if (!empty($triwulanIds))    $query->whereIn('triwulan', $triwulanIds);

        $records = $query->get();

        $totalUtang = (int) $records->sum('utang');
        $totalPembayaran = (int) $records->sum('pembayaran');
        $totalSisa = $totalUtang - $totalPembayaran;
        $persentase = $totalUtang > 0 ? round(($totalPembayaran / $totalUtang) * 100, 1) : 0.0;

        // Peringkat Sisa Utang per Kabupaten
        $perKabupaten = $records
            ->groupBy('kabupaten_id')
            ->map(fn ($g) => [
                'kabupaten' => $g->first()->kabupaten->nama ?? '',
                'sisa' => (int) $g->sum('sisa'),
                'pembayaran' => (int) $g->sum('pembayaran'),
                'utang' => (int) $g->sum('utang'),
            ])
            ->sortByDesc(fn ($x) => $x['sisa'])
            ->values();

        // Realisasi vs Sisa per Jenis Pajak
        $perJenisPajak = $records
            ->groupBy('jenis_pajak_id')
            ->map(fn ($g) => [
                'nama' => $g->first()->jenisPajak->nama ?? '',
                'sisa' => (int) $g->sum('sisa'),
                'dibayar' => (int) $g->sum('pembayaran'),
                'utang' => (int) $g->sum('utang'),
            ]);

        // Tren per Triwulan
        $labelsTw = [1 => 'TW I', 2 => 'TW II', 3 => 'TW III', 4 => 'TW IV'];
        $perTriwulan = [];
        for ($i = 1; $i <= 4; $i++) {
            $tw = $records->where('triwulan', $i);
            $perTriwulan[] = [
                'label' => $labelsTw[$i],
                'utang' => (int) $tw->sum('utang'),
                'pembayaran' => (int) $tw->sum('pembayaran'),
            ];
        }

        // Proporsi sisa per jenis pajak
        $proporsi = $perJenisPajak
            ->filter(fn ($x) => $x['sisa'] > 0)
            ->map(fn ($x) => [
                'nama' => $x['nama'],
                'sisa' => $x['sisa'],
                'pct' => $totalSisa > 0 ? round(($x['sisa'] / $totalSisa) * 100, 1) : 0.0,
            ]);

        // Key insights
        $topKabupaten = $perKabupaten->first();
        $bestPct = $perJenisPajak
            ->filter(fn ($x) => $x['utang'] > 0)
            ->map(fn ($x) => ['nama' => $x['nama'], 'pct' => round(($x['dibayar'] / $x['utang']) * 100, 1)])
            ->sortByDesc('pct')
            ->first();

        $insights = [
            'regional' => [
                'label' => 'REGIONAL SPOTLIGHT',
                'text' => sprintf(
                    '%s memiliki sisa utang tertinggi sebesar Rp %s.',
                    $topKabupaten['kabupaten'] ?? '-',
                    number_format($topKabupaten['sisa'] ?? 0, 0, ',', '.')
                ),
            ],
            'performance' => [
                'label' => 'COLLECTION PERFORMANCE',
                'text' => $bestPct
                    ? sprintf('Kategori pajak %s memiliki persentase pembayaran tertinggi %s%%.', $bestPct['nama'], $bestPct['pct'])
                    : 'Belum ada data pembayaran.',
            ],
            'quarterly' => [
                'label' => 'QUARTERLY SUMMARY',
                'text' => sprintf(
                    'Untuk %s (%s), total Rp %s kewajiban dengan realisasi %s%%. Tersisa Rp %s yang belum diselesaikan.',
                    !empty($tahunAnggaran) ? 'TAHUN ' . implode(' & ', $tahunAnggaran) : 'Semua Tahun',
                    !empty($triwulanIds) ? 'Triwulan ' . implode(' & ', $triwulanIds) : 'Semua Triwulan',
                    number_format($totalUtang, 0, ',', '.'),
                    $persentase,
                    number_format($totalSisa, 0, ',', '.')
                ),
            ],
        ];

        // Filter option lists
        $tahunList = TransaksiUtang::query()->select('tahun_anggaran')->distinct()->orderBy('tahun_anggaran')->pluck('tahun_anggaran')->all();
        $kabList = Kabupaten::query()->orderBy('nama')->get(['id', 'nama']);
        $jpkList = JenisPajak::query()->orderBy('nama')->get(['id', 'nama', 'kode']);

        $filter = [
            'tahun' => array_values($tahunAnggaran),
            'kabupaten' => array_values($kabupatenIds),
            'jenis_pajak' => array_values($jenisPajakIds),
            'triwulan' => array_values($triwulanIds),
        ];

        // Colors for charts
        $colorPalette = ['#2563EB', '#10B981', '#EF4444', '#F59E0B', '#8B5CF6', '#EC4899'];
        $doughnutColors = array_map(fn($i) => $colorPalette[$i % count($colorPalette)], range(0, max(0, count($proporsi) - 1)));

        $data = [
            'tahun' => $tahunList,
            'kabupatens' => $kabList,
            'jenisPajak' => $jpkList,
            'totalUtang' => $totalUtang,
            'totalPembayaran' => $totalPembayaran,
            'totalSisa' => $totalSisa,
            'persentase' => $persentase,
            'perKabupaten' => $perKabupaten,
            'perJenisPajak' => $perJenisPajak,
            'perTriwulan' => $perTriwulan,
            'proporsi' => $proporsi,
            'insights' => $insights,
            'filter' => $filter,
            'doughnutColors' => $doughnutColors,
        ];

        return view('dashboard.index', $data);
    }
}
