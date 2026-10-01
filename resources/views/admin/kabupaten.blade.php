@extends('layouts.admin')

@section('page-title', 'Kabupaten/Kota')
@section('page-subtitle', 'Master data wilayah penerima DBH')

@section('content')
<div class="rounded-xl border border-slate-200 bg-white shadow-sm">
    <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-200 p-4">
        <div>
            <h2 class="text-sm font-bold text-slate-900">Daftar Kabupaten/Kota</h2>
            <p class="text-xs text-slate-500">Kelola wilayah yang menerima Dana Bagi Hasil</p>
        </div>
        <button onclick="document.getElementById('addForm').classList.remove('hidden'); document.getElementById('nama').focus()"
            class="flex items-center gap-1.5 rounded-lg bg-blue-600 px-3.5 py-2 text-xs font-semibold text-white transition-colors hover:bg-blue-700">
            <svg xmlns="http://www.w3.org/2000/svg" class="size-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg>
            Tambah Wilayah
        </button>
    </div>

    <form id="addForm" method="POST" action="{{ route('admin.kabupaten.store') }}" class="hidden border-b border-slate-200 bg-slate-50 p-4">
        @csrf
        <div class="flex flex-wrap items-end gap-3">
            <div class="flex-1 min-w-48">
                <label for="nama" class="mb-1 block text-xs font-semibold text-slate-700">Nama Kabupaten/Kota</label>
                <input id="nama" name="nama" type="text" required maxlength="64"
                    placeholder="contoh: BENGKULU TENGAH"
                    class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
                @error('nama')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div class="flex gap-2">
                <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 text-xs font-semibold text-white hover:bg-blue-700">Simpan</button>
                <button type="button" onclick="document.getElementById('addForm').classList.add('hidden')"
                    class="rounded-lg bg-slate-200 px-4 py-2 text-xs font-medium text-slate-700 hover:bg-slate-300">Batal</button>
            </div>
        </div>
    </form>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-xs">
            <thead class="bg-slate-50 text-slate-500">
                <tr>
                    <th class="px-4 py-2.5 font-semibold">No</th>
                    <th class="px-4 py-2.5 font-semibold">Nama</th>
                    <th class="px-4 py-2.5 font-semibold">Jumlah Record</th>
                    <th class="px-4 py-2.5 text-right font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($kabupatens as $i => $kab)
                    <tr class="hover:bg-slate-50">
                        <td class="px-4 py-3 text-slate-500">{{ $i + 1 }}</td>
                        <td class="px-4 py-3 font-medium text-slate-900">{{ $kab->nama }}</td>
                        <td class="px-4 py-3 text-slate-500">{{ $kab->transaksi_count }}</td>
                        <td class="px-4 py-3 text-right">
                            <button
                                onclick="startEditKab({{ $kab->id }}, '{{ $kab->nama }}')"
                                class="rounded-md px-2.5 py-1 font-medium text-blue-600 hover:bg-blue-50">
                                Ubah
                            </button>
                            <form method="POST" action="{{ route('admin.kabupaten.destroy', $kab) }}" class="inline"
                                  onsubmit="return confirm('Hapus {{ $kab->nama }}? Record transaksi terkait juga akan ikut terhapus.')">
                                @csrf @method('DELETE')
                                <button type="submit" class="rounded-md px-2.5 py-1 font-medium text-red-600 hover:bg-red-50">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Edit forms (hidden, filled by JS) --}}
    @foreach($kabupatens as $kab)
        <form id="edit-kab-{{ $kab->id }}" method="POST" action="{{ route('admin.kabupaten.update', $kab) }}" class="hidden">
            @csrf @method('PUT')
            <input type="hidden" name="nama" value="{{ $kab->nama }}">
        </form>
    @endforeach
</div>

<script>
function startEditKab(id, nama) {
    const nilai = prompt('Ubah nama kabupaten/kota:', nama);
    if (nilai === null || nilai.trim() === '') return;
    const form = document.getElementById('edit-kab-' + id);
    form.querySelector('input[name=nama]').value = nilai.trim();
    form.submit();
}
</script>
@endsection
