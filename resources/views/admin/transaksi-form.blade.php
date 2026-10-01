@extends('layouts.admin')

@section('page-title', isset($transaksi) && !empty($transaksi->id) ? 'Ubah Transaksi' : 'Tambah Transaksi')
@section('page-subtitle', isset($transaksi) && !empty($transaksi->id) ? 'Perbarui rincian utang' : 'Input rincian utang baru')

@section('content')
@php $isEdit = isset($transaksi) && !empty($transaksi->id); @endphp

<div class="mx-auto max-w-2xl">
    <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-200 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-sm font-bold text-slate-900">{{ $isEdit ? 'Form Ubah Data' : 'Form Input Data' }}</h2>
                    <p class="text-xs text-slate-500">Sisa utang dihitung otomatis: Utang − Pembayaran</p>
                </div>
                <a href="{{ route('admin.transaksi') }}"
                    class="flex items-center gap-1 text-xs font-medium text-slate-500 hover:text-slate-900">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
                    Kembali
                </a>
            </div>
        </div>

        @if(session('error'))
            <div class="mx-4 mt-4 rounded-lg border border-red-200 bg-red-50 px-4 py-2.5 text-xs text-red-700">
                {{ session('error') }}
            </div>
        @endif
        @if($errors->any())
            <div class="mx-4 mt-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-xs text-red-700">
                <p class="font-semibold">Formulir belum valid:</p>
                <ul class="mt-1 list-inside list-disc space-y-0.5">
                    @foreach($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST"
              action="{{ $isEdit ? route('admin.transaksi.update', $transaksi) : route('admin.transaksi.store') }}"
              class="p-4 sm:p-5">
            @csrf
            @if($isEdit) @method('PUT') @endif

            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label for="tahun_anggaran" class="mb-1.5 block text-xs font-semibold text-slate-700">Tahun Anggaran *</label>
                    <input id="tahun_anggaran" type="number" name="tahun_anggaran" required min="2000" max="2100"
                        value="{{ old('tahun_anggaran', $transaksi->tahun_anggaran ?? '') }}"
                        class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
                    @error('tahun_anggaran')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="triwulan" class="mb-1.5 block text-xs font-semibold text-slate-700">Triwulan *</label>
                    <select id="triwulan" name="triwulan" required
                            class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
                        @for($i = 1; $i <= 4; $i++)
                            <option value="{{ $i }}" @selected((int)old('triwulan', $transaksi->triwulan ?? 0) === $i)>Triwulan {{ $i }}</option>
                        @endfor
                    </select>
                    @error('triwulan')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="kabupaten_id" class="mb-1.5 block text-xs font-semibold text-slate-700">Kabupaten/Kota *</label>
                    <select id="kabupaten_id" name="kabupaten_id" required
                            class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
                        <option value="">— Pilih wilayah —</option>
                        @foreach($kabupatens as $k)
                            <option value="{{ $k->id }}" @selected((int)old('kabupaten_id', $transaksi->kabupaten_id ?? 0) === $k->id)>
                                {{ $k->nama }}
                            </option>
                        @endforeach
                    </select>
                    @error('kabupaten_id')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="jenis_pajak_id" class="mb-1.5 block text-xs font-semibold text-slate-700">Jenis Pajak *</label>
                    <select id="jenis_pajak_id" name="jenis_pajak_id" required
                            class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
                        <option value="">— Pilih pajak —</option>
                        @foreach($jenisPajak as $j)
                            <option value="{{ $j->id }}" @selected((int)old('jenis_pajak_id', $transaksi->jenis_pajak_id ?? 0) === $j->id)>
                                {{ $j->nama }}
                            </option>
                        @endforeach
                    </select>
                    @error('jenis_pajak_id')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="utang" class="mb-1.5 block text-xs font-semibold text-slate-700">Jumlah Utang (Rp) *</label>
                    <input id="utang" type="number" name="utang" required min="0" step="1"
                        value="{{ old('utang', $transaksi->utang ?? '') }}"
                        oninput="calcSisa()"
                        class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm tabular-nums focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
                    @error('utang')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="pembayaran" class="mb-1.5 block text-xs font-semibold text-slate-700">Jumlah Pembayaran (Rp) *</label>
                    <input id="pembayaran" type="number" name="pembayaran" required min="0" step="1"
                        value="{{ old('pembayaran', $transaksi->pembayaran ?? '') }}"
                        oninput="calcSisa()"
                        class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm tabular-nums focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
                    @error('pembayaran')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    <div id="sisa-preview" class="mt-2 hidden rounded-lg bg-slate-50 px-3 py-2 text-xs">
                        Sisa utang: <span id="sisa-preview-val" class="font-semibold text-red-600">Rp 0</span>
                    </div>
                </div>
            </div>

            <div class="mt-5 flex gap-2">
                <button type="submit"
                    class="rounded-lg bg-blue-600 px-5 py-2 text-xs font-semibold text-white transition-colors hover:bg-blue-700">
                    Simpan Data
                </button>
                <a href="{{ route('admin.transaksi') }}"
                    class="rounded-lg bg-slate-200 px-5 py-2 text-xs font-medium text-slate-700 hover:bg-slate-300">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

<script>
function calcSisa() {
    const utang = parseInt(document.getElementById('utang').value) || 0;
    const bayar = parseInt(document.getElementById('pembayaran').value) || 0;
    const sisaEl = document.getElementById('sisa-preview');
    const valEl = document.getElementById('sisa-preview-val');
    if (utang > 0 || bayar > 0) {
        sisaEl.classList.remove('hidden');
        const sisa = Math.max(0, utang - bayar);
        valEl.textContent = 'Rp ' + sisa.toLocaleString('id-ID');
        valEl.className = 'font-semibold ' + (sisa > 0 ? 'text-red-600' : 'text-emerald-600');
    } else {
        sisaEl.classList.add('hidden');
    }
}
calcSisa();
</script>
@endsection
