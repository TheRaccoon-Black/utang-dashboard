@extends('layouts.admin')

@section('page-title', 'Transaksi Utang')
@section('page-subtitle', 'Rekap rincian utang dan pembayaran per triwulan')

@section('content')
<div class="rounded-xl border border-slate-200 bg-white shadow-sm">
    <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-200 p-4">
        <div>
            <h2 class="text-sm font-bold text-slate-900">Daftar Transaksi</h2>
            <p class="text-xs text-slate-500">Total {{ $transaksi->total() }} record</p>
        </div>
        <a href="{{ route('admin.transaksi.create') }}"
            class="flex items-center gap-1.5 rounded-lg bg-blue-600 px-3.5 py-2 text-xs font-semibold text-white transition-colors hover:bg-blue-700">
            <svg xmlns="http://www.w3.org/2000/svg" class="size-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg>
            Tambah Data
        </a>
    </div>

    {{-- Search + filter --}}
    <form method="GET" action="{{ route('admin.transaksi') }}" class="flex flex-wrap items-center gap-2 border-b border-slate-200 bg-slate-50 p-3">
        <div class="relative flex-1 min-w-48">
            <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400" style="width:16px;height:16px" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari kabupaten, pajak, atau tahun..."
                class="w-full rounded-lg border border-slate-300 bg-white py-2 pl-9 pr-3 text-xs focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
        </div>
        <select name="tahun" class="rounded-lg border border-slate-300 bg-white px-3 py-2 text-xs focus:border-blue-500 focus:outline-none">
            <option value="">Semua Tahun</option>
            @foreach(\App\Models\TransaksiUtang::select('tahun_anggaran')->distinct()->orderBy('tahun_anggaran')->pluck('tahun_anggaran') as $t)
                <option value="{{ $t }}" @selected((string)$t === request('tahun'))>{{ $t }}</option>
            @endforeach
        </select>
        <button type="submit" class="rounded-lg bg-slate-900 px-4 py-2 text-xs font-semibold text-white hover:bg-slate-700">Cari</button>
        <a href="{{ route('admin.transaksi') }}" class="rounded-lg bg-slate-200 px-4 py-2 text-xs font-medium text-slate-700 hover:bg-slate-300">Reset</a>
    </form>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-xs">
            <thead class="bg-slate-50 text-slate-500">
                <tr>
                    <th class="px-4 py-2.5 font-semibold">Tahun</th>
                    <th class="px-4 py-2.5 font-semibold">Kabupaten/Kota</th>
                    <th class="px-4 py-2.5 font-semibold">Jenis Pajak</th>
                    <th class="px-4 py-2.5 font-semibold">Triwulan</th>
                    <th class="px-4 py-2.5 text-right font-semibold">Utang</th>
                    <th class="px-4 py-2.5 text-right font-semibold">Dibayar</th>
                    <th class="px-4 py-2.5 text-right font-semibold">Sisa</th>
                    <th class="px-4 py-2.5 text-right font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($transaksi as $t)
                    <tr class="hover:bg-slate-50">
                        <td class="px-4 py-3 text-slate-700">{{ $t->tahun_anggaran }}</td>
                        <td class="px-4 py-3 font-medium text-slate-900">{{ $t->kabupaten->nama }}</td>
                        <td class="px-4 py-3 text-slate-700">{{ $t->jenisPajak->nama }}</td>
                        <td class="px-4 py-3 text-slate-700">Triwulan {{ $t->triwulan }}</td>
                        <td class="px-4 py-3 text-right tabular-nums text-slate-700">Rp {{ number_format($t->utang, 0, ',', '.') }}</td>
                        <td class="px-4 py-3 text-right tabular-nums {{ $t->pembayaran > 0 ? 'text-emerald-700' : 'text-slate-400' }}">
                            Rp {{ number_format($t->pembayaran, 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-3 text-right tabular-nums {{ $t->sisa > 0 ? 'text-red-600' : 'text-slate-400' }}">
                            Rp {{ number_format($t->sisa, 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-3 text-right whitespace-nowrap">
                            <a href="{{ route('admin.transaksi.edit', $t) }}"
                                class="rounded-md px-2.5 py-1 font-medium text-blue-600 hover:bg-blue-50">Ubah</a>
                            <form method="POST" action="{{ route('admin.transaksi.destroy', $t) }}" class="inline"
                                  onsubmit="return confirm('Hapus record ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="rounded-md px-2.5 py-1 font-medium text-red-600 hover:bg-red-50">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-4 py-12 text-center">
                            <p class="text-sm font-medium text-slate-500">Belum ada data yang cocok</p>
                            <p class="mt-1 text-xs text-slate-400">Coba ubah kata kunci atau filter, atau tambahkan data baru.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="flex flex-col gap-2 border-t border-slate-200 p-3 sm:flex-row sm:items-center sm:justify-between">
        <span class="text-xs text-slate-500">
            Menampilkan {{ $transaksi->firstItem() ?? 0 }}–{{ $transaksi->lastItem() ?? 0 }} dari {{ $transaksi->total() }} record
        </span>
        <div class="text-xs">
            {{ $transaksi->links('pagination::tailwind') }}
        </div>
    </div>
</div>
@endsection
