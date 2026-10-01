@extends('layouts.admin')

@section('page-title', 'Dashboard Admin')
@section('page-subtitle', 'Ringkasan sistem dan akses cepat ke manajemen data')

@section('content')
<div class="flex flex-col gap-4">

    {{-- Stat cards --}}
    <div class="grid grid-cols-2 gap-3 xl:grid-cols-4">
        <div class="rounded-xl border border-slate-200 bg-white p-4">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-medium uppercase tracking-wider text-slate-400">Total Record Transaksi</span>
                <span class="flex size-8 items-center justify-center rounded-lg bg-slate-100 text-slate-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01"/></svg>
                </span>
            </div>
            <p class="mt-1.5 text-2xl font-bold text-slate-900 tabular-nums">{{ number_format($stats['totalRecord']) }}</p>
            <p class="text-[11px] text-slate-400">Data rincian utang per triwulan</p>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-4">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-medium uppercase tracking-wider text-slate-400">Total Kewajiban</span>
                <span class="flex size-8 items-center justify-center rounded-lg bg-blue-50 text-blue-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                </span>
            </div>
            <p class="mt-1.5 text-2xl font-bold text-blue-600 tabular-nums">
                Rp {{ number_format(round($stats['totalUtang'] / 1000000000), 1) }} M
            </p>
            <p class="text-[11px] text-slate-400">Total seluruh kewajiban utang</p>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-4">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-medium uppercase tracking-wider text-slate-400">Total Realisasi</span>
                <span class="flex size-8 items-center justify-center rounded-lg bg-emerald-50 text-emerald-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.92-9.14"/><path d="m9 11 3 3L22 4"/></svg>
                </span>
            </div>
            <p class="mt-1.5 text-2xl font-bold text-emerald-600 tabular-nums">
                Rp {{ number_format(round($stats['totalPembayaran'] / 1000000000), 1) }} M
            </p>
            <p class="text-[11px] text-slate-400">Total pembayaran yang terealisasi</p>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-4">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-medium uppercase tracking-wider text-slate-400">Sisa Utang</span>
                <span class="flex size-8 items-center justify-center rounded-lg bg-red-50 text-red-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3"/><path d="M12 9v4M12 17h.01"/></svg>
                </span>
            </div>
            <p class="mt-1.5 text-2xl font-bold text-red-600 tabular-nums">
                Rp {{ number_format(round($stats['totalSisa'] / 1000000000), 1) }} M
            </p>
            <p class="text-[11px] text-slate-400">Kewajiban belum terbayar</p>
        </div>
    </div>

    {{-- Quick action links --}}
    <div>
        <h2 class="mb-3 text-sm font-bold text-slate-900">Kelola Data</h2>
        <div class="grid gap-3 md:grid-cols-3">
            @foreach($quickLinks as $link)
                <a href="{{ route($link['route']) }}"
                    class="group rounded-xl border border-slate-200 bg-white p-4 shadow-sm transition-all hover:-translate-y-0.5 hover:border-slate-300 hover:shadow-md">
                    <div class="flex items-start gap-3">
                        <span class="flex size-10 shrink-0 items-center justify-center rounded-lg {{ $link['color'] }}">
                            @if($link['icon'] === 'map')
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 18.2V21l-1.2.9"/><path d="M8 21l-4.9-3.4"/><path d="M6.4 14.6 3.5 15.9"/><path d="m6.9 9.7-3.4 1.4"/><path d="M14 4.2 20.3 7.6"/><path d="M8.4 11.7 1.8 7.6"/><path d="M8 21h8"/><path d="M8 17.6V4"/></svg>
                            @elseif($link['icon'] === 'tag')
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12.586 2.586A2 2 0 0 0 11.172 2H4a2 2 0 0 0-2 2v7.172a2 2 0 0 0 .586 1.414l8.7 8.7a2 2 0 0 0 2.828 0l6.172-6.172a2 2 0 0 0 0-2.828z"/><circle cx="7.5" cy="7.5" r=".5"/></svg>
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01"/></svg>
                            @endif
                        </span>
                        <div class="min-w-0">
                            <p class="text-sm font-semibold text-slate-900 group-hover:text-blue-600">{{ $link['title'] }}</p>
                            <p class="mt-0.5 text-xs text-slate-500">{{ $link['desc'] }}</p>
                        </div>
                    </div>
                    <div class="mt-3 flex items-center justify-between">
                        <span class="text-[11px] font-medium text-slate-400">
                            @if($link['route'] === 'admin.kabupaten')
                                {{ $stats['totalKabupaten'] }} wilayah
                            @elseif($link['route'] === 'admin.jenis_pajak')
                                {{ $stats['totalJenisPajak'] }} kategori
                            @else
                                {{ $stats['totalRecord'] }} record
                            @endif
                        </span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4 text-slate-300 transition-transform group-hover:translate-x-0.5 group-hover:text-slate-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                    </div>
                </a>
            @endforeach
        </div>
    </div>

    {{-- Recent activity --}}
    <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
        <div class="flex items-center justify-between border-b border-slate-200 p-4">
            <div>
                <h3 class="text-sm font-bold text-slate-900">Aktivitas Terakhir</h3>
                <p class="text-xs text-slate-500">Record yang terakhir ditambahkan atau diubah</p>
            </div>
            <a href="{{ route('admin.transaksi') }}" class="text-xs font-semibold text-blue-600 hover:underline">Lihat semua →</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-500">
                    <tr>
                        <th class="px-4 py-2.5 font-medium">Tahun</th>
                        <th class="px-4 py-2.5 font-medium">Kabupaten/Kota</th>
                        <th class="px-4 py-2.5 font-medium">Jenis Pajak</th>
                        <th class="px-4 py-2.5 font-medium">Triwulan</th>
                        <th class="px-4 py-2.5 text-right font-medium">Utang</th>
                        <th class="px-4 py-2.5 text-right font-medium">Dibayar</th>
                        <th class="px-4 py-2.5 text-right font-medium">Diubah</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($lastRecords as $t)
                        <tr class="hover:bg-slate-50">
                            <td class="px-4 py-2.5 text-slate-700">{{ $t->tahun_anggaran }}</td>
                            <td class="px-4 py-2.5 font-medium text-slate-900">{{ $t->kabupaten->nama }}</td>
                            <td class="px-4 py-2.5 text-slate-700">{{ $t->jenisPajak->nama }}</td>
                            <td class="px-4 py-2.5 text-slate-700">TW {{ $t->triwulan }}</td>
                            <td class="px-4 py-2.5 text-right tabular-nums text-slate-700">Rp {{ number_format($t->utang, 0, ',', '.') }}</td>
                            <td class="px-4 py-2.5 text-right tabular-nums {{ $t->pembayaran > 0 ? 'text-emerald-700' : 'text-slate-400' }}">
                                Rp {{ number_format($t->pembayaran, 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-2.5 text-right text-slate-400">{{ \Carbon\Carbon::parse($t->updated_at)->translatedFormat('d M Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-slate-400">Belum ada data.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
